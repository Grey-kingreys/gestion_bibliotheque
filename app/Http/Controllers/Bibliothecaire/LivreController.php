<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Categorie;
use App\Models\Auteur;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    /**
     * Liste des livres
     */
    public function index(Request $request)
    {
        $query = Livre::with(['categorie', 'auteurs']);

        // Recherche
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre disponibilité
        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        $livres = $query->latest()->paginate(15);
        $categories = Categorie::orderBy('libelle')->get();

        return view('bibliothecaire.livres.index', compact('livres', 'categories'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Categorie::orderBy('libelle')->get();
        $auteurs = Auteur::orderBy('nom')->get();
        
        return view('bibliothecaire.livres.create', compact('categories', 'auteurs'));
    }

    /**
     * Enregistrer un nouveau livre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:livres',
            'resume' => 'nullable|string',
            'nombre_exemplaires' => 'required|integer|min:1',
            'editeur' => 'nullable|string|max:255',
            'annee_publication' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'categorie_id' => 'required|exists:categories,id',
            'auteurs' => 'required|array|min:1',
            'auteurs.*' => 'exists:auteurs,id',
        ], [
            'titre.required' => 'Le titre est obligatoire',
            'isbn.required' => 'L\'ISBN est obligatoire',
            'isbn.unique' => 'Cet ISBN existe déjà',
            'nombre_exemplaires.required' => 'Le nombre d\'exemplaires est obligatoire',
            'nombre_exemplaires.min' => 'Le nombre d\'exemplaires doit être au moins 1',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'auteurs.required' => 'Au moins un auteur est obligatoire',
        ]);

        $livre = Livre::create([
            'titre' => $validated['titre'],
            'isbn' => $validated['isbn'],
            'resume' => $validated['resume'],
            'nombre_exemplaires' => $validated['nombre_exemplaires'],
            'nombre_disponibles' => $validated['nombre_exemplaires'],
            'editeur' => $validated['editeur'],
            'annee_publication' => $validated['annee_publication'],
            'categorie_id' => $validated['categorie_id'],
            'disponible' => true,
        ]);

        // Attacher les auteurs
        $livre->auteurs()->attach($validated['auteurs']);

        return redirect()->route('bibliothecaire.livres.index')
            ->with('success', 'Livre ajouté avec succès !');
    }

    /**
     * Afficher les détails d'un livre
     */
    public function show(Livre $livre)
    {
        $livre->load(['categorie', 'auteurs', 'emprunts.user']);
        
        return view('bibliothecaire.livres.show', compact('livre'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Livre $livre)
    {
        $categories = Categorie::orderBy('libelle')->get();
        $auteurs = Auteur::orderBy('nom')->get();
        
        return view('bibliothecaire.livres.edit', compact('livre', 'categories', 'auteurs'));
    }

    /**
     * Mettre à jour un livre
     */
    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:livres,isbn,' . $livre->id,
            'resume' => 'nullable|string',
            'nombre_exemplaires' => 'required|integer|min:1',
            'editeur' => 'nullable|string|max:255',
            'annee_publication' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'categorie_id' => 'required|exists:categories,id',
            'auteurs' => 'required|array|min:1',
            'auteurs.*' => 'exists:auteurs,id',
        ], [
            'titre.required' => 'Le titre est obligatoire',
            'isbn.required' => 'L\'ISBN est obligatoire',
            'isbn.unique' => 'Cet ISBN existe déjà',
            'nombre_exemplaires.required' => 'Le nombre d\'exemplaires est obligatoire',
            'categorie_id.required' => 'La catégorie est obligatoire',
            'auteurs.required' => 'Au moins un auteur est obligatoire',
        ]);

        // Calculer le nouveau nombre disponible
        $difference = $validated['nombre_exemplaires'] - $livre->nombre_exemplaires;
        $nouveauDisponible = $livre->nombre_disponibles + $difference;
        
        $livre->update([
            'titre' => $validated['titre'],
            'isbn' => $validated['isbn'],
            'resume' => $validated['resume'],
            'nombre_exemplaires' => $validated['nombre_exemplaires'],
            'nombre_disponibles' => max(0, $nouveauDisponible),
            'editeur' => $validated['editeur'],
            'annee_publication' => $validated['annee_publication'],
            'categorie_id' => $validated['categorie_id'],
        ]);

        // Synchroniser les auteurs
        $livre->auteurs()->sync($validated['auteurs']);

        return redirect()->route('bibliothecaire.livres.index')
            ->with('success', 'Livre modifié avec succès !');
    }

    /**
     * Marquer comme indisponible (soft delete)
     */
    public function toggleDisponibilite(Livre $livre)
    {
        $livre->update([
            'disponible' => !$livre->disponible
        ]);

        $message = $livre->disponible 
            ? 'Livre marqué comme disponible' 
            : 'Livre marqué comme indisponible';

        return back()->with('success', $message);
    }
}