<?php

namespace App\Http\Controllers\Lecteur;

use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    /**
     * Demander un emprunt (Lecteur)
     */
    public function demander(Request $request, Livre $livre)
    {
        // Vérifier si le livre est disponible
        if (!$livre->estDisponible()) {
            return back()->withErrors(['livre' => 'Ce livre n\'est pas disponible']);
        }

        // Vérifier si l'utilisateur n'a pas déjà emprunté ce livre
        $empruntExistant = Emprunt::where('user_id', auth()->id())
            ->where('livre_id', $livre->id)
            ->whereIn('statut', ['en_attente', 'valide', 'en_cours'])
            ->first();

        if ($empruntExistant) {
            return back()->withErrors(['livre' => 'Vous avez déjà une demande en cours pour ce livre']);
        }

        // Créer la demande d'emprunt
        Emprunt::create([
            'user_id' => auth()->id(),
            'livre_id' => $livre->id,
            'date_emprunt' => Carbon::now(),
            'date_retour_prevue' => Carbon::now()->addDays(14), // 2 semaines
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Demande d\'emprunt envoyée avec succès !');
    }

    /**
     * Mes emprunts (Lecteur)
     */
    public function mesEmprunts(Request $request)
    {
        $query = Emprunt::where('user_id', auth()->id())
            ->with(['livre.auteurs', 'livre.categorie']);

        // Filtrer par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'en_cours') {
                $query->enCours();
            } elseif ($request->statut === 'retourne') {
                $query->where('statut', 'retourne');
            } elseif ($request->statut === 'en_retard') {
                $query->enRetard();
            }
        }

        $emprunts = $query->latest()->paginate(10);

        // Statistiques
        $stats = [
            'en_cours' => Emprunt::where('user_id', auth()->id())->enCours()->count(),
            'en_retard' => Emprunt::where('user_id', auth()->id())->enRetard()->count(),
            'total' => Emprunt::where('user_id', auth()->id())->count(),
        ];

        return view('lecteur.mes-emprunts', compact('emprunts', 'stats'));
    }

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
            } elseif ($request->statut === 'en_retard') {
                $query->enRetard();
            }
        }

        $emprunts = $query->latest()->paginate(15);

        // Statistiques
        $stats = [
            'en_attente' => Emprunt::enAttente()->count(),
            'en_cours' => Emprunt::enCours()->count(),
            'en_retard' => Emprunt::enRetard()->count(),
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
            'statut' => 'retourne', // On utilise ce statut pour "annulé"
            'commentaire' => $request->motif,
        ]);

        return back()->with('success', 'Demande rejetée');
    }
}