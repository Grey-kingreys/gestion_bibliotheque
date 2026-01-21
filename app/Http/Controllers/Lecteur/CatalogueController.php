<?php

namespace App\Http\Controllers\Lecteur;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /**
     * Afficher le catalogue des livres (accessible à tous)
     */
    public function index(Request $request)
    {
        $query = Livre::with(['categorie', 'auteurs']);

        // Recherche par titre
        if ($request->filled('titre')) {
            $query->where('titre', 'like', '%' . $request->titre . '%');
        }

        // Recherche par auteur
        if ($request->filled('auteur')) {
            $query->whereHas('auteurs', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->auteur . '%')
                  ->orWhere('prenom', 'like', '%' . $request->auteur . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre disponibilité
        if ($request->filled('disponible') && $request->disponible == '1') {
            $query->where('disponible', true)
                  ->where('nombre_disponibles', '>', 0);
        }

        $livres = $query->latest()->paginate(12);
        $categories = Categorie::orderBy('libelle')->get();

        return view('lecteur.catalogue', compact('livres', 'categories'));
    }

    /**
     * Afficher le détail d'un livre
     */
    public function show(Livre $livre)
    {
        $livre->load(['categorie', 'auteurs', 'emprunts' => function($query) {
            $query->latest()->limit(5);
        }]);

        return view('lecteur.livre-detail', compact('livre'));
    }
}