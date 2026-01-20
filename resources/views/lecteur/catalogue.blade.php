@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            📚 Catalogue des Livres
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Découvrez notre collection de {{ $livres->total() }} livres
        </p>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('livres.catalogue') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche par titre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Titre
                    </label>
                    <input 
                        type="text" 
                        name="titre" 
                        value="{{ request('titre') }}"
                        placeholder="Rechercher un titre..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <!-- Recherche par auteur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Auteur
                    </label>
                    <input 
                        type="text" 
                        name="auteur" 
                        value="{{ request('auteur') }}"
                        placeholder="Nom de l'auteur..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <!-- Filtre par catégorie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catégorie
                    </label>
                    <select 
                        name="categorie"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Toutes</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre disponibilité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Disponibilité
                    </label>
                    <select 
                        name="disponible"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Tous</option>
                        <option value="1" {{ request('disponible') == '1' ? 'selected' : '' }}>Disponibles</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition"
                >
                    🔍 Rechercher
                </button>
                <a 
                    href="{{ route('livres.catalogue') }}"
                    class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-medium transition"
                >
                    ✖️ Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des livres -->
    @if($livres->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun livre trouvé</h3>
            <p class="text-gray-600 dark:text-gray-400">Essayez de modifier vos critères de recherche</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($livres as $livre)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-xl transition overflow-hidden">
                    <!-- Image placeholder -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-48 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>

                    <div class="p-6">
                        <!-- Catégorie -->
                        <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full mb-3">
                            {{ $livre->categorie->libelle }}
                        </span>

                        <!-- Titre -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $livre->titre }}
                        </h3>

                        <!-- Auteurs -->
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Par {{ $livre->auteurs->pluck('full_name')->implode(', ') }}
                        </p>

                        <!-- Résumé -->
                        @if($livre->resume)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $livre->resume }}
                            </p>
                        @endif

                        <!-- Disponibilité -->
                        <div class="flex items-center justify-between mb-4">
                            @if($livre->estDisponible())
                                <span class="flex items-center text-sm text-green-600 dark:text-green-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $livre->nombre_disponibles }} disponible(s)
                                </span>
                            @else
                                <span class="flex items-center text-sm text-red-600 dark:text-red-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Indisponible
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a 
                                href="{{ route('livres.show', $livre) }}"
                                class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium text-center transition"
                            >
                                Détails
                            </a>
                            @if(auth()->check() && auth()->user()->isLecteur() && $livre->estDisponible())
                                <form method="POST" action="{{ route('lecteur.emprunter', $livre) }}" class="flex-1">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Emprunter
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $livres->links() }}
        </div>
    @endif
</div>
@endsection