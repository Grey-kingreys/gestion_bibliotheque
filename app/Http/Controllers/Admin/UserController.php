<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs avec filtres
     */
    public function index(Request $request)
    {
        $query = User::withCount('emprunts');

        // Recherche par nom, prénom, login ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('login', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par statut (actif/inactif)
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }

        $users = $query->latest()->paginate(15);

        // Statistiques
        $stats = [
            'total' => User::count(),
            'actifs' => User::where('actif', true)->count(),
            'admins' => User::where('role', 'Radmin')->count(),
            'bibliothecaires' => User::where('role', 'Rbibliothecaire')->count(),
            'lecteurs' => User::where('role', 'Rlecteur')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Formulaire de création d'utilisateur
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'role' => 'required|in:Radmin,Rbibliothecaire,Rlecteur',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'actif' => 'nullable|boolean', // ✅ AJOUT DE NULLABLE
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'telephone.required' => 'Le téléphone est obligatoire',
            'role.required' => 'Le rôle est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);

        // Générer le login selon le rôle
        $login = $this->generateLogin($validated['role']);

        User::create([
            'login' => $login,
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'actif' => $request->has('actif'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur créé avec succès ! Login : {$login}");
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['emprunts.livre.auteurs', 'emprunts.livre.categorie']);

        // Statistiques de l'utilisateur - AJOUT DE "REJETE"
        $stats = [
            'total_emprunts' => $user->emprunts->count(),
            'en_cours' => $user->emprunts->where('statut', 'en_cours')->count(),
            'en_retard' => $user->emprunts->filter(fn($e) => $e->estEnRetard())->count(),
            'retournes' => $user->emprunts->where('statut', 'retourne')->count(),
            'rejetes' => $user->emprunts->where('statut', 'rejete')->count(), // ✅ AJOUTÉ
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'role' => 'required|in:Radmin,Rbibliothecaire,Rlecteur',
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'actif' => 'nullable|boolean', // ✅ AJOUT DE NULLABLE
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'telephone.required' => 'Le téléphone est obligatoire',
            'role.required' => 'Le rôle est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);

        $data = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'role' => $validated['role'],
            'actif' => $request->has('actif'),
        ];

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès !');
    }

    /**
     * Activer/Désactiver un utilisateur (soft delete)
     */
    public function toggleActif(User $user)
    {
        // Empêcher de désactiver le dernier admin
        if ($user->isAdmin() && $user->actif) {
            $nbAdminsActifs = User::where('role', 'Radmin')
                ->where('actif', true)
                ->count();
            
            if ($nbAdminsActifs <= 1) {
                return back()->withErrors([
                    'user' => 'Impossible de désactiver le dernier administrateur actif'
                ]);
            }
        }

        $user->update(['actif' => !$user->actif]);

        $message = $user->actif 
            ? 'Utilisateur activé avec succès' 
            : 'Utilisateur désactivé avec succès';

        return back()->with('success', $message);
    }

    /**
     * ✅ NOUVEAU : Supprimer définitivement un utilisateur de la base de données
     */
    public function destroy(User $user)
    {
        // Empêcher de supprimer le dernier admin
        if ($user->isAdmin()) {
            $nbAdmins = User::where('role', 'Radmin')->count();
            
            if ($nbAdmins <= 1) {
                return back()->withErrors([
                    'user' => 'Impossible de supprimer le dernier administrateur'
                ]);
            }
        }

        // Empêcher de se supprimer soi-même
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'user' => 'Vous ne pouvez pas supprimer votre propre compte'
            ]);
        }

        // Vérifier s'il y a des emprunts en cours ou en retard
        $empruntsActifs = $user->emprunts()
            ->whereIn('statut', ['en_cours', 'en_retard', 'en_attente'])
            ->count();

        if ($empruntsActifs > 0) {
            return back()->withErrors([
                'user' => "Impossible de supprimer cet utilisateur : il a {$empruntsActifs} emprunt(s) actif(s). Veuillez d'abord traiter ses emprunts."
            ]);
        }

        // Sauvegarder le nom pour le message
        $nomComplet = $user->full_name;

        // SUPPRESSION DÉFINITIVE de la base de données
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur {$nomComplet} a été définitivement supprimé de la base de données.");
    }

    /**
     * Générer un login unique selon le rôle
     */
    private function generateLogin(string $role): string
    {
        $year = date('Y');
        
        switch ($role) {
            case 'Radmin':
                $prefix = 'ADMIN';
                break;
            case 'Rbibliothecaire':
                $prefix = 'BIBLIO';
                break;
            default:
                $prefix = 'ETU';
        }

        // Trouver le dernier numéro utilisé
        $lastUser = User::where('login', 'like', "{$prefix}%")
            ->orderBy('login', 'desc')
            ->first();

        if ($lastUser) {
            // Extraire le numéro (les derniers chiffres)
            preg_match('/\d+$/', $lastUser->login, $matches);
            $lastNumber = isset($matches[0]) ? intval($matches[0]) : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "{$prefix}{$newNumber}";
    }
}