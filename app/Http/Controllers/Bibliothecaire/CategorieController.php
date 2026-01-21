<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Liste des catégories
     */
    public function index(Request $request)
    {
        $query = Categorie::withCount('livres');

        if ($request->filled('search')) {
            $query->where('libelle', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('libelle')->paginate(15);

        return view('bibliothecaire.categories.index', compact('categories'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('bibliothecaire.categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:50|unique:categories',
            'description' => 'nullable|string',
        ], [
            'libelle.required' => 'Le libellé est obligatoire',
            'libelle.unique' => 'Cette catégorie existe déjà',
            'libelle.max' => 'Le libellé ne doit pas dépasser 50 caractères',
        ]);

        Categorie::create($validated);

        return redirect()->route('bibliothecaire.categories.index')
            ->with('success', 'Catégorie ajoutée avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Categorie $categorie)
    {
        return view('bibliothecaire.categories.edit', compact('categorie'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:50|unique:categories,libelle,' . $categorie->id,
            'description' => 'nullable|string',
        ], [
            'libelle.required' => 'Le libellé est obligatoire',
            'libelle.unique' => 'Cette catégorie existe déjà',
        ]);

        $categorie->update($validated);

        return redirect()->route('bibliothecaire.categories.index')
            ->with('success', 'Catégorie modifiée avec succès !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier s'il y a des livres associés
        if ($categorie->livres()->count() > 0) {
            return back()->withErrors([
                'categorie' => 'Impossible de supprimer cette catégorie car elle contient des livres.'
            ]);
        }

        $categorie->delete();

        return redirect()->route('bibliothecaire.categories.index')
            ->with('success', 'Catégorie supprimée avec succès !');
    }
}