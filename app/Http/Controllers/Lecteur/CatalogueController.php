<?php

namespace App\Http\Controllers\Lecteur;

use App\Models\Livre;
use App\Models\Categorie;
use App\Models\Auteur;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /**
     * Afficher le catalogue des livres (accessible à tous)
     */
    public function catalogue(Request $request)
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
        if ($request->filled('disponible')) {
            $query->where('disponible', true)
                  ->where('nombre_disponibles', '>', 0);
        }

        $livres = $query->latest()->paginate(12);
        $categories = Categorie::orderBy('libelle')->get();

        return view('livres.catalogue', compact('livres', 'categories'));
    }

    /**
     * Afficher le détail d'un livre
     */
    public function show(Livre $livre)
    {
        $livre->load(['categorie', 'auteurs', 'emprunts' => function($query) {
            $query->latest()->limit(5);
        }]);

        return view('livres.show', compact('livre'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::orderBy('libelle')->get();
        $auteurs = Auteur::orderBy('nom')->get();

        return view('livres.create', compact('categories', 'auteurs'));
    }

    /**
     * Enregistrer un nouveau livre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'isbn' => 'required|string|unique:livres,isbn',
            'resume' => 'nullable|string',
            'nombre_exemplaires' => 'required|integer|min:1',
            'editeur' => 'nullable|string|max:255',
            'annee_publication' => 'nullable|integer|min:1000|max:' . date('Y'),
            'categorie_id' => 'required|exists:categories,id',
            'auteurs' => 'required|array|min:1',
            'auteurs.*' => 'exists:auteurs,id',
        ], [
            'titre.required' => 'Le titre est obligatoire',
            'isbn.required' => 'L\'ISBN est obligatoire',
            'isbn.unique' => 'Cet ISBN existe déjà',
            'nombre_exemplaires.required' => 'Le nombre d\'exemplaires est obligatoire',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'auteurs.required' => 'Au moins un auteur est requis',
        ]);

        $validated['nombre_disponibles'] = $validated['nombre_exemplaires'];

        $livre = Livre::create($validated);
        $livre->auteurs()->attach($request->auteurs);

        return redirect()
            ->route('bibliothecaire.livres.index')
            ->with('success', 'Livre ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Livre $livre)
    {
        $categories = Categorie::orderBy('libelle')->get();
        $auteurs = Auteur::orderBy('nom')->get();

        return view('livres.edit', compact('livre', 'categories', 'auteurs'));
    }

    /**
     * Mettre à jour un livre
     */
    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'isbn' => 'required|string|unique:livres,isbn,' . $livre->id,
            'resume' => 'nullable|string',
            'nombre_exemplaires' => 'required|integer|min:1',
            'editeur' => 'nullable|string|max:255',
            'annee_publication' => 'nullable|integer|min:1000|max:' . date('Y'),
            'categorie_id' => 'required|exists:categories,id',
            'auteurs' => 'required|array|min:1',
            'auteurs.*' => 'exists:auteurs,id',
            'disponible' => 'sometimes|boolean',
        ]);

        $livre->update($validated);
        $livre->auteurs()->sync($request->auteurs);

        return redirect()
            ->route('bibliothecaire.livres.index')
            ->with('success', 'Livre modifié avec succès !');
    }

    /**
     * Marquer un livre comme indisponible
     */
    public function toggleDisponibilite(Livre $livre)
    {
        $livre->update([
            'disponible' => !$livre->disponible
        ]);

        $statut = $livre->disponible ? 'disponible' : 'indisponible';

        return back()->with('success', "Livre marqué comme {$statut}");
    }

    /**
     * Liste des livres (pour bibliothécaire/admin)
     */
    public function index(Request $request)
    {
        $query = Livre::with(['categorie', 'auteurs']);

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
        }

        $livres = $query->latest()->paginate(15);

        return view('livres.index', compact('livres'));
    }
}