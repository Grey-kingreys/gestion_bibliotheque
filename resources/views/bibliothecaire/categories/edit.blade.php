@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('bibliothecaire.categories.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">✏️ Modifier la Catégorie</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        <form method="POST" action="{{ route('bibliothecaire.categories.update', $categorie) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Libellé <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="libelle" 
                    value="{{ old('libelle', $categorie->libelle) }}"
                    required
                    maxlength="50"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea 
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >{{ old('description', $categorie->description) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                    Mettre à jour
                </button>
                <a href="{{ route('bibliothecaire.categories.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-medium text-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection