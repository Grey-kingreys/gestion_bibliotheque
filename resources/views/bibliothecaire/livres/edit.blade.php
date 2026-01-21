@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('bibliothecaire.livres.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">✏️ Modifier le Livre</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        <form method="POST" action="{{ route('bibliothecaire.livres.update', $livre) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $livre->titre) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ISBN <span class="text-red-500">*</span></label>
                <input type="text" name="isbn" value="{{ old('isbn', $livre->isbn) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie <span class="text-red-500">*</span></label>
                <select name="categorie_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>{{ $categorie->libelle }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Auteur(s) <span class="text-red-500">*</span></label>
                <select name="auteurs[]" multiple required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500" size="5">
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}" {{ in_array($auteur->id, old('auteurs', $livre->auteurs->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $auteur->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Éditeur</label>
                    <input type="text" name="editeur" value="{{ old('editeur', $livre->editeur) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Année</label>
                    <input type="number" name="annee_publication" value="{{ old('annee_publication', $livre->annee_publication) }}" min="1900" max="{{ date('Y') + 1 }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre d'exemplaires <span class="text-red-500">*</span></label>
                <input type="number" name="nombre_exemplaires" value="{{ old('nombre_exemplaires', $livre->nombre_exemplaires) }}" min="1" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                <p class="text-sm text-gray-500 mt-1">Actuellement {{ $livre->nombre_disponibles }} disponible(s)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Résumé</label>
                <textarea name="resume" rows="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('resume', $livre->resume) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">Mettre à jour</button>
                <a href="{{ route('bibliothecaire.livres.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-medium text-center">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection