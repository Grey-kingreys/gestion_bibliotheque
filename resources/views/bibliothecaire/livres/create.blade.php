@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('bibliothecaire.livres.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            ➕ Ajouter un Livre
        </h1>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700 dark:text-red-400">✗ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bibliothecaire.livres.store') }}" class="space-y-6">
            @csrf

            <!-- Titre -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="titre" 
                    value="{{ old('titre') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    placeholder="Introduction aux Algorithmes"
                >
            </div>

            <!-- ISBN -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    ISBN <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="isbn" 
                    value="{{ old('isbn') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    placeholder="978-2100545261"
                >
            </div>

            <!-- Catégorie -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select 
                    name="categorie_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Auteurs -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Auteur(s) <span class="text-red-500">*</span>
                </label>
                <select 
                    name="auteurs[]"
                    multiple
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    size="5"
                >
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}" {{ in_array($auteur->id, old('auteurs', [])) ? 'selected' : '' }}>
                            {{ $auteur->full_name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs auteurs</p>
            </div>

            <!-- Editeur et Année -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Éditeur
                    </label>
                    <input 
                        type="text" 
                        name="editeur" 
                        value="{{ old('editeur') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        placeholder="Dunod"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Année de publication
                    </label>
                    <input 
                        type="number" 
                        name="annee_publication" 
                        value="{{ old('annee_publication') }}"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        placeholder="2023"
                    >
                </div>
            </div>

            <!-- Nombre d'exemplaires -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre d'exemplaires <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="nombre_exemplaires" 
                    value="{{ old('nombre_exemplaires', 1) }}"
                    min="1"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <!-- Résumé -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Résumé
                </label>
                <textarea 
                    name="resume" 
                    rows="5"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    placeholder="Description du livre..."
                >{{ old('resume') }}</textarea>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition"
                >
                    ✓ Enregistrer
                </button>
                <a 
                    href="{{ route('bibliothecaire.livres.index') }}"
                    class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-medium text-center transition"
                >
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection