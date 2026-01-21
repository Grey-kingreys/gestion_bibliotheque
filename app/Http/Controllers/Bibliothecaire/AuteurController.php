<?php

namespace App\Http\Controllers\Bibliothecaire;

use App\Http\Controllers\Controller;
use App\Models\Auteur;
use Illuminate\Http\Request;

class AuteurController extends Controller
{
    /**
     * Liste des auteurs
     */
    public function index(Request $request)
    {
        $query = Auteur::withCount('livres');

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%');
        }

        $auteurs = $query->orderBy('nom')->paginate(15);

        return view('bibliothecaire.auteurs.index', compact('auteurs'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('bibliothecaire.auteurs.create');
    }

    /**
     * Enregistrer un nouvel auteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'biographie' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire',
        ]);

        Auteur::create($validated);

        return redirect()->route('bibliothecaire.auteurs.index')
            ->with('success', 'Auteur ajouté avec succès !');
    }

    /**
     * Afficher les détails d'un auteur
     */
    public function show(Auteur $auteur)
    {
        $auteur->load('livres.categorie');
        
        return view('bibliothecaire.auteurs.show', compact('auteur'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Auteur $auteur)
    {
        return view('bibliothecaire.auteurs.edit', compact('auteur'));
    }

    /**
     * Mettre à jour un auteur
     */
    public function update(Request $request, Auteur $auteur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'biographie' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom est obligatoire',
        ]);

        $auteur->update($validated);

        return redirect()->route('bibliothecaire.auteurs.index')
            ->with('success', 'Auteur modifié avec succès !');
    }

    /**
     * Supprimer un auteur
     */
    public function destroy(Auteur $auteur)
    {
        // Vérifier s'il y a des livres associés
        if ($auteur->livres()->count() > 0) {
            return back()->withErrors([
                'auteur' => 'Impossible de supprimer cet auteur car il a des livres associés.'
            ]);
        }

        $auteur->delete();

        return redirect()->route('bibliothecaire.auteurs.index')
            ->with('success', 'Auteur supprimé avec succès !');
    }
}