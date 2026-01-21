{{-- resources/views/lecteur/livre-detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('catalogue') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour au catalogue
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
                    <!-- Disponibilité -->
                    <div class="mb-6">
                        @if($livre->estDisponible())
                            <div class="flex items-center justify-center bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-lg">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="font-semibold">Disponible</div>
                                    <div class="text-sm">{{ $livre->nombre_disponibles }} exemplaire(s)</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 p-4 rounded-lg">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="font-semibold">Non disponible</div>
                            </div>
                        @endif
                    </div>

                    <!-- Bouton d'action -->
                    @auth
                        @if(auth()->user()->isLecteur() && $livre->estDisponible())
                            <form method="POST" action="{{ route('lecteur.emprunter', $livre) }}" class="mb-4">
                                @csrf
                                <button 
                                    type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Emprunter ce livre
                                </button>
                            </form>
                        @endif
                    @else
                        <a 
                            href="{{ route('login') }}"
                            class="w-full block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition text-center"
                        >
                            Connectez-vous pour emprunter
                        </a>
                    @endauth

                    <!-- Informations supplémentaires -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">ISBN</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $livre->isbn }}</span>
                        </div>
                        @if($livre->editeur)
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Éditeur</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $livre->editeur }}</span>
                            </div>
                        @endif
                        @if($livre->annee_publication)
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Année</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $livre->annee_publication }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">Exemplaires</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $livre->nombre_exemplaires }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Détails -->
        <div class="lg:col-span-2">
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
                        <span class="ml-1 font-medium">{{ $auteur->full_name }}</span>
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

                <!-- Informations sur les auteurs -->
                @if($livre->auteurs->whereNotNull('biographie')->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            À propos des auteurs
                        </h2>
                        @foreach($livre->auteurs->whereNotNull('biographie') as $auteur)
                            <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $auteur->full_name }}
                                    @if($auteur->nationalite)
                                        <span class="text-sm text-gray-600 dark:text-gray-400">({{ $auteur->nationalite }})</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $auteur->biographie }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Historique des emprunts récents -->
                @if($livre->emprunts->count() > 0)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activité récente
                        </h2>
                        <div class="space-y-2">
                            @foreach($livre->emprunts->take(5) as $emprunt)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">
                                        Emprunté le {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                    </span>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($emprunt->statut === 'en_cours') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($emprunt->statut === 'retourne') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($emprunt->statut === 'en_retard') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @endif">
                                        @if($emprunt->statut === 'en_cours') En cours
                                        @elseif($emprunt->statut === 'retourne') Retourné
                                        @elseif($emprunt->statut === 'en_retard') En retard
                                        @else En attente
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection