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

        // Filtrer par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'en_attente') {
                $query->enAttente();
            } elseif ($request->statut === 'en_cours') {
                $query->enCours();
            } elseif ($request->statut === 'retourne') {
                $query->retourne();
            } elseif ($request->statut === 'en_retard') {
                $query->enRetard();
            } elseif ($request->statut === 'rejete') {
                $query->rejete();
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
            return back()->withErrors(['emprunt' => 'Cet emprunt ne peut pas être validé']);
        }

        // Vérifier la disponibilité
        if (!$emprunt->livre->estDisponible()) {
            return back()->withErrors(['emprunt' => 'Le livre n\'est plus disponible']);
        }

        // Emprunter le livre
        $emprunt->livre->emprunter();

        // Mettre à jour l'emprunt
        $emprunt->update([
            'statut' => 'en_cours',
            'date_emprunt' => Carbon::now(),
        ]);

        return back()->with('success', 'Emprunt validé avec succès !');
    }

    /**
     * Valider le retour d'un livre (Bibliothécaire)
     */
    public function retour(Emprunt $emprunt)
    {
        if (!in_array($emprunt->statut, ['en_cours', 'en_retard'])) {
            return back()->withErrors(['emprunt' => 'Cet emprunt ne peut pas être retourné']);
        }

        // Marquer comme retourné
        $emprunt->marquerCommeRetourne();

        return back()->with('success', 'Retour validé avec succès !');
    }

    /**
     * Rejeter une demande (Bibliothécaire)
     */
    public function rejeter(Request $request, Emprunt $emprunt)
    {
        if ($emprunt->statut !== 'en_attente') {
            return back()->withErrors(['emprunt' => 'Cette demande ne peut pas être rejetée']);
        }

        $request->validate([
            'motif' => 'required|string|max:500',
        ]);

        $emprunt->update([
            'statut' => 'rejete',
            'commentaire' => $request->motif,
        ]);

        return back()->with('success', 'Demande rejetée');
    }
}