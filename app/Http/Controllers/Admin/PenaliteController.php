<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penalite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenaliteController extends Controller
{
    /**
     * Liste des pénalités avec filtres
     */
    public function index(Request $request)
    {
        $query = Penalite::with(['emprunt.user', 'emprunt.livre']);

        // Filtre par statut de paiement
        if ($request->filled('payee')) {
            $query->where('payee', $request->payee);
        }

        // Recherche par utilisateur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('emprunt.user', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('login', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $penalites = $query->paginate(20);

        // Statistiques
        $stats = [
            'total_penalites' => Penalite::count(),
            'montant_total' => Penalite::sum('montant'),
            'montant_paye' => Penalite::where('payee', true)->sum('montant'),
            'montant_impaye' => Penalite::where('payee', false)->sum('montant'),
            'non_payees' => Penalite::where('payee', false)->count(),
        ];

        return view('admin.penalites.index', compact('penalites', 'stats'));
    }

    /**
     * Afficher les détails d'une pénalité
     */
    public function show(Penalite $penalite)
    {
        $penalite->load(['emprunt.user', 'emprunt.livre.auteurs']);
        
        return view('admin.penalites.show', compact('penalite'));
    }

    /**
     * Marquer une pénalité comme payée
     */
    public function marquerPayee(Penalite $penalite)
    {
        if ($penalite->payee) {
            return back()->withErrors(['penalite' => 'Cette pénalité est déjà marquée comme payée.']);
        }

        $penalite->marquerCommePayee();

        return back()->with('success', 'Pénalité marquée comme payée avec succès !');
    }

    /**
     * Annuler une pénalité (Admin uniquement)
     */
    public function annuler(Penalite $penalite)
    {
        if ($penalite->payee) {
            return back()->withErrors(['penalite' => 'Impossible d\'annuler une pénalité déjà payée.']);
        }

        // Sauvegarder les infos pour le message
        $montant = $penalite->montant_formate;
        $user = $penalite->emprunt->user->full_name;

        $penalite->delete();

        return redirect()->route('admin.penalites.index')
            ->with('success', "Pénalité de {$montant} pour {$user} annulée avec succès.");
    }

    /**
     * Vue des pénalités par utilisateur
     */
    public function parUtilisateur(Request $request)
    {
        $query = User::withCount(['emprunts as penalites_count' => function($q) {
                $q->whereHas('penalite');
            }])
            ->with(['emprunts.penalite' => function($q) {
                $q->latest();
            }])
            ->having('penalites_count', '>', 0);

        // Filtre par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'impayees') {
                $query->whereHas('emprunts.penalite', function($q) {
                    $q->where('payee', false);
                });
            }
        }

        $users = $query->paginate(15);

        // Calculer les totaux pour chaque utilisateur
        $users->getCollection()->transform(function($user) {
            $user->total_penalites = $user->emprunts
                ->pluck('penalite')
                ->whereNotNull()
                ->sum('montant');
            
            $user->penalites_impayees = $user->emprunts
                ->pluck('penalite')
                ->whereNotNull()
                ->where('payee', false)
                ->sum('montant');

            return $user;
        });

        return view('admin.penalites.par-utilisateur', compact('users'));
    }

    /**
     * Exporter les pénalités en CSV
     */
    public function export()
    {
        $penalites = Penalite::with(['emprunt.user', 'emprunt.livre'])->get();

        $filename = 'penalites_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($penalites) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Login',
                'Livre',
                'Jours de Retard',
                'Montant (GNF)',
                'Statut',
                'Date Création',
                'Date Paiement',
                'Motif',
            ]);

            // Données
            foreach ($penalites as $penalite) {
                fputcsv($file, [
                    $penalite->id,
                    $penalite->emprunt->user->full_name,
                    $penalite->emprunt->user->login,
                    $penalite->emprunt->livre->titre,
                    $penalite->jours_retard,
                    $penalite->montant,
                    $penalite->payee ? 'Payée' : 'Non payée',
                    $penalite->created_at->format('d/m/Y H:i'),
                    $penalite->date_paiement ? $penalite->date_paiement->format('d/m/Y H:i') : 'N/A',
                    $penalite->motif ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}