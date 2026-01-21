{{-- resources/views/bibliothecaire/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            📊 Dashboard Bibliothécaire
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Bienvenue {{ auth()->user()->full_name }} ! Vue d'ensemble de la bibliothèque
        </p>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Livres -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Livres</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Livre::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm text-blue-100">
                {{ \App\Models\Livre::where('disponible', true)->count() }} disponibles
            </div>
        </div>

        <!-- Emprunts en attente -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-yellow-100 text-sm mb-1">En attente</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Emprunt::where('statut', 'en_attente')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm text-yellow-100">
                À valider
            </div>
        </div>

        <!-- Emprunts en cours -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm mb-1">En cours</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Emprunt::where('statut', 'en_cours')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm text-green-100">
                Emprunts actifs
            </div>
        </div>

        <!-- En retard -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-red-100 text-sm mb-1">En retard</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Emprunt::where('statut', 'en_retard')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm text-red-100">
                Nécessite attention
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Demandes en attente -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Demandes en attente
                </h2>
            </div>
            <div class="p-6">
                @php
                    $demandesEnAttente = \App\Models\Emprunt::with(['user', 'livre'])
                        ->where('statut', 'en_attente')
                        ->latest()
                        ->limit(5)
                        ->get();
                @endphp

                @if($demandesEnAttente->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400">Aucune demande en attente</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($demandesEnAttente as $emprunt)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $emprunt->livre->titre }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $emprunt->user->full_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $emprunt->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('bibliothecaire.emprunts.index') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 text-sm font-medium">
                                    Traiter →
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($demandesEnAttente->count() >= 5)
                        <div class="mt-4 text-center">
                            <a href="#" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 text-sm font-medium">
                                Voir toutes les demandes →
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Retours en retard -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Retours en retard
                </h2>
            </div>
            <div class="p-6">
                @php
                    $empruntsEnRetard = \App\Models\Emprunt::with(['user', 'livre'])
                        ->where('statut', 'en_cours')
                        ->where('date_retour_prevue', '<', now())
                        ->orderBy('date_retour_prevue')
                        ->limit(5)
                        ->get();
                @endphp

                @if($empruntsEnRetard->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto w-12 h-12 text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400">Aucun retard</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($empruntsEnRetard as $emprunt)
                            <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $emprunt->livre->titre }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $emprunt->user->full_name }}</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">
                                        Retard: {{ $emprunt->joursDeRetard() }} jour(s)
                                    </p>
                                </div>
                                <a href="{{ route('bibliothecaire.emprunts.index') }}" class="text-red-600 hover:text-red-700 dark:text-red-400 text-sm font-medium">
                                    Contacter →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Accès rapides -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600">Ajouter un livre</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Nouveau livre au catalogue</p>
                </div>
            </div>
        </a>

        <a href="{{ route('bibliothecaire.emprunts.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600">Gérer les emprunts</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Valider et suivre</p>
                </div>
            </div>
        </a>

        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-purple-600">Catégories</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Gérer les catégories</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection