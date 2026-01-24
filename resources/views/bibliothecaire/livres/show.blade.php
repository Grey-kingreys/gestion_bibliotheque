{{-- resources/views/bibliothecaire/livres/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('bibliothecaire.livres.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Image et actions -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden sticky top-6">
                <!-- Image du livre -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-96 flex items-center justify-center">
                    <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>

                <div class="p-6">
                    <!-- Statut disponibilité -->
                    <div class="mb-6">
                        @if($livre->estDisponible())
                            <div class="flex items-center justify-center bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-lg">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="font-semibold">Disponible</div>
                                    <div class="text-sm">{{ $livre->nombre_disponibles }} / {{ $livre->nombre_exemplaires }} exemplaire(s)</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 p-4 rounded-lg">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="font-semibold">Indisponible</div>
                                    <div class="text-sm">{{ $livre->nombre_disponibles }} / {{ $livre->nombre_exemplaires }} exemplaire(s)</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Boutons d'action -->
                    <div class="space-y-3 mb-6">
                        <a 
                            href="{{ route('bibliothecaire.livres.edit', $livre) }}"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('bibliothecaire.livres.toggle-disponibilite', $livre) }}">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $livre->disponible ? 'Marquer indisponible' : 'Marquer disponible' }}
                            </button>
                        </form>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="space-y-3 text-sm border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">ISBN</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $livre->isbn }}</span>
                        </div>
                        @if($livre->editeur)
                            <div class="flex justify-between py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Éditeur</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $livre->editeur }}</span>
                            </div>
                        @endif
                        @if($livre->annee_publication)
                            <div class="flex justify-between py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Année</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $livre->annee_publication }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Ajouté le</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $livre->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Détails -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations principales -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <!-- Catégorie -->
                <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm font-medium rounded-full mb-4">
                    {{ $livre->categorie->libelle }}
                </span>

                <!-- Titre -->
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $livre->titre }}
                </h1>

                <!-- Auteurs -->
                <div class="flex items-center text-lg text-gray-600 dark:text-gray-400 mb-8">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Par 
                    @foreach($livre->auteurs as $auteur)
                        <a href="{{ route('bibliothecaire.auteurs.show', $auteur) }}" class="ml-1 font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                            {{ $auteur->full_name }}
                        </a>
                        @if(!$loop->last), @endif
                    @endforeach
                </div>

                <!-- Résumé -->
                @if($livre->resume)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Résumé
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $livre->resume }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Statistiques des emprunts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques d'emprunt
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">En cours</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $livre->emprunts->where('statut', 'en_cours')->count() }}
                        </p>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <p class="text-sm text-green-600 dark:text-green-400 mb-1">Retournés</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                            {{ $livre->emprunts->where('statut', 'retourne')->count() }}
                        </p>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                        <p class="text-sm text-red-600 dark:text-red-400 mb-1">En retard</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300">
                            {{ $livre->emprunts->where('statut', 'en_retard')->count() }}
                        </p>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <p class="text-sm text-purple-600 dark:text-purple-400 mb-1">Total</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                            {{ $livre->emprunts->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Emprunts en cours -->
            @if($livre->emprunts->whereIn('statut', ['en_cours', 'en_retard'])->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Emprunts en cours
                    </h2>

                    <div class="space-y-3">
                        @foreach($livre->emprunts->whereIn('statut', ['en_cours', 'en_retard'])->sortByDesc('created_at') as $emprunt)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $emprunt->user->full_name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $emprunt->user->login }} • {{ $emprunt->user->email }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-2 text-xs">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            Emprunté: {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                        </span>
                                        <span class="{{ $emprunt->estEnRetard() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                            Retour: {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if($emprunt->estEnRetard())
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-medium rounded">
                                            Retard: {{ $emprunt->joursDeRetard() }}j
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium rounded">
                                            En cours
                                        </span>
                                    @endif

                                    <a href="{{ route('bibliothecaire.emprunts.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                        Gérer →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Historique des emprunts -->
            @if($livre->emprunts->where('statut', 'retourne')->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Historique des retours
                    </h2>

                    <div class="space-y-2">
                        @foreach($livre->emprunts->where('statut', 'retourne')->sortByDesc('date_retour_effective')->take(10) as $emprunt)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $emprunt->user->full_name }}</span>
                                    <span class="text-gray-500 dark:text-gray-400 mx-2">•</span>
                                    <span class="text-gray-600 dark:text-gray-400">
                                        {{ $emprunt->date_emprunt->format('d/m/Y') }} → {{ $emprunt->date_retour_effective->format('d/m/Y') }}
                                    </span>
                                </div>
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-medium rounded">
                                    ✓ Retourné
                                </span>
                            </div>
                        @endforeach

                        @if($livre->emprunts->where('statut', 'retourne')->count() > 10)
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                                Et {{ $livre->emprunts->where('statut', 'retourne')->count() - 10 }} autre(s) emprunt(s)
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection