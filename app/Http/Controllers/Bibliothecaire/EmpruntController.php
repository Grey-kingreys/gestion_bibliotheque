<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    /**
     * Liste des emprunts (Bibliothécaire)
     */
    public function index(Request $request)
    {
        $query = Emprunt::with(['user', 'livre.auteurs']);

        // Mettre à jour les statuts des emprunts en retard
        $this->mettreAJourStatutsEnRetard();

        // Filtrer par statut
        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'en_attente':
                    $query->enAttente();
                    break;
                case 'en_cours':
                    $query->enCours();
                    break;
                case 'retourne':
                    $query->retourne();
                    break;
                case 'en_retard':
                    $query->enRetard();
                    break;
                case 'rejete':
                    $query->rejete();
                    break;
            }
        }

        $emprunts = $query->latest()->paginate(15);

        // Statistiques
        $stats = [
            'en_attente' => Emprunt::enAttente()->count(),
            'en_cours' => Emprunt::enCours()->count(),
            'retourne' => Emprunt::retourne()->count(),
            'en_retard' => Emprunt::enRetard()->count(),
            'rejete' => Emprunt::rejete()->count(),
        ];

        return view('bibliothecaire.emprunts.index', compact('emprunts', 'stats'));
    }

    /**
     * Valider un emprunt (Bibliothécaire)
     */
    public function valider(Emprunt $emprunt)
    {
        if ($emprunt->statut !== 'en_attente') {
            return back()->withErrors(['emprunt' => 'Cet emprunt ne peut pas être validé. Statut actuel : ' . $emprunt->statut]);
        }

        // Vérifier la disponibilité
        if (!$emprunt->livre->estDisponible()) {
            return back()->withErrors(['emprunt' => 'Le livre n\'est plus disponible (exemplaires disponibles : ' . $emprunt->livre->nombre_disponibles . ')']);
        }

        // Utiliser la méthode du modèle pour valider
        if ($emprunt->valider()) {
            return back()->with('success', 'Emprunt validé avec succès ! Le livre a été marqué comme emprunté.');
        }

        return back()->withErrors(['emprunt' => 'Impossible de valider cet emprunt']);
    }

    /**
     * Valider le retour d'un livre (Bibliothécaire)
     */
    public function retour(Emprunt $emprunt)
    {
        if (!in_array($emprunt->statut, ['en_cours', 'en_retard'])) {
            return back()->withErrors(['emprunt' => 'Cet emprunt ne peut pas être retourné. Statut actuel : ' . $emprunt->statut]);
        }

        try {
            // Marquer comme retourné
            $emprunt->marquerCommeRetourne();

            $message = 'Retour validé avec succès !';
            
            // Informer s'il y a eu une pénalité
            if ($emprunt->penalite) {
                $message .= ' Une pénalité de ' . $emprunt->penalite->montant_formate . ' a été créée pour ' . $emprunt->penalite->jours_retard . ' jour(s) de retard.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['emprunt' => 'Erreur lors du retour : ' . $e->getMessage()]);
        }
    }

    /**
     * Rejeter une demande (Bibliothécaire)
     */
    public function rejeter(Request $request, Emprunt $emprunt)
    {
        if ($emprunt->statut !== 'en_attente') {
            return back()->withErrors(['emprunt' => 'Cette demande ne peut pas être rejetée. Statut actuel : ' . $emprunt->statut]);
        }

        $request->validate([
            'motif' => 'required|string|max:500',
        ], [
            'motif.required' => 'Le motif du rejet est obligatoire',
            'motif.max' => 'Le motif ne peut pas dépasser 500 caractères',
        ]);

        $emprunt->rejeter($request->motif);

        return back()->with('success', 'Demande rejetée avec succès');
    }

    /**
     * Mettre à jour automatiquement les statuts des emprunts en retard
     */
    private function mettreAJourStatutsEnRetard(): void
    {
        // Récupérer tous les emprunts en cours qui sont en retard
        $empruntsEnRetard = Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', Carbon::now())
            ->whereNull('date_retour_effective')
            ->get();

        // Mettre à jour leur statut
        foreach ($empruntsEnRetard as $emprunt) {
            $emprunt->update(['statut' => 'en_retard']);
        }
    }

    /**
     * Afficher les détails d'un emprunt
     */
    public function show(Emprunt $emprunt)
    {
        $emprunt->load(['user', 'livre.auteurs', 'livre.categorie', 'penalite']);
        
        // Mettre à jour le statut si nécessaire
        $emprunt->updateStatutSiNecessaire();
        
        return view('bibliothecaire.emprunts.show', compact('emprunt'));
    }
}