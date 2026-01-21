@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">🏷️ Gestion des Catégories</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $categories->total() }} catégorie(s)</p>
        </div>
        <a href="{{ route('bibliothecaire.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <p class="text-sm text-green-700 dark:text-green-400">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700 dark:text-red-400">✗ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $categorie)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $categorie->libelle }}</h3>
                        @if($categorie->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $categorie->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $categorie->livres_count }} livre(s)
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('bibliothecaire.categories.edit', $categorie) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Modifier
                        </a>
                        @if($categorie->livres_count == 0)
                            <form method="POST" action="{{ route('bibliothecaire.categories.destroy', $categorie) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">{{ $categories->links() }}</div>
</div>
@endsection