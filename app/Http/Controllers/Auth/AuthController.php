<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Le matricule est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
        ]);

        $credentials = [
            'login' => $request->login,
            'password' => $request->password,
        ];

        // Vérifier si l'utilisateur est actif
        $user = User::where('login', $request->login)->first();
        
        if ($user && !$user->actif) {
            return back()->withErrors([
                'login' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ])->withInput($request->only('login'));
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'login' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('login'));
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'telephone.required' => 'Le téléphone est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
        ]);

        // Générer le login (matricule)
        $login = $this->generateLogin();

        $user = User::create([
            'login' => $login,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => 'Rlecteur', // Par défaut, nouveau utilisateur = lecteur
            'actif' => true,
        ]);

        Auth::login($user);

        // Redirection selon le rôle (nouveau utilisateur = toujours lecteur)
        return redirect()->route('lecteur.catalogue')
            ->with('success', "Compte créé avec succès ! Votre matricule est : {$login}");
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Générer un matricule unique
     */
    private function generateLogin(): string
    {
        $year = date('Y');
        $lastUser = User::where('login', 'like', "ETU{$year}%")
            ->orderBy('login', 'desc')
            ->first();

        if ($lastUser) {
            $lastNumber = intval(substr($lastUser->login, -2));
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '01';
        }

        return "ETU{$year}{$newNumber}";
    }

    /**
     * Rediriger selon le rôle
     */
    private function redirectByRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Bienvenue, Administrateur !');
        }

        if ($user->isBibliothecaire()) {
            return redirect()->route('bibliothecaire.dashboard')
                ->with('success', 'Bienvenue, Bibliothécaire !');
        }

        return redirect()->route('lecteur.catalogue')
            ->with('success', 'Bienvenue, ' . $user->prenom . ' !');
    }
}