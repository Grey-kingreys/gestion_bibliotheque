<?php

namespace App\Http\Controllers\Lecteur;

use App\Http\Controllers\Controller;
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
}