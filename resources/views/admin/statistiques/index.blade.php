{{-- resources/views/admin/statistiques/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                📊 Statistiques & Analytics
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Vue d'ensemble complète de l'activité de la bibliothèque
            </p>
        </div>
        <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exporter CSV
        </a>
    </div>

    <!-- Statistiques Globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Utilisateurs</p>
                    <p class="text-3xl font-bold">{{ $statsGlobales['total_utilisateurs'] }}</p>
                    <p class="text-purple-100 text-xs mt-1">{{ $statsGlobales['utilisateurs_actifs'] }} actifs</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Livres</p>
                    <p class="text-3xl font-bold">{{ $statsGlobales['total_livres'] }}</p>
                    <p class="text-blue-100 text-xs mt-1">{{ $statsGlobales['livres_disponibles'] }} disponibles</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm mb-1">Emprunts</p>
                    <p class="text-3xl font-bold">{{ $statsGlobales['total_emprunts'] }}</p>
                    <p class="text-green-100 text-xs mt-1">{{ $statsGlobales['emprunts_en_cours'] }} en cours</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-red-100 text-sm mb-1">Pénalités</p>
                    <p class="text-3xl font-bold">{{ number_format($statsGlobales['total_penalites'], 0, ',', ' ') }}</p>
                    <p class="text-red-100 text-xs mt-1">{{ number_format($statsGlobales['penalites_non_payees'], 0, ',', ' ') }} GNF impayées</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Période -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Aujourd'hui</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Emprunts</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['aujourd_hui']['emprunts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Retours</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['aujourd_hui']['retours'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Cette Semaine</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Emprunts</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['cette_semaine']['emprunts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Retours</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['cette_semaine']['retours'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Ce Mois</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Emprunts</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['ce_mois']['emprunts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Retours</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $statsParPeriode['ce_mois']['retours'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Importants -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Taux de Retard</h3>
                <span class="px-3 py-1 {{ $tauxRetard > 10 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }} text-sm font-medium rounded-full">
                    {{ $tauxRetard }}%
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Pourcentage d'emprunts retournés en retard
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Durée Moyenne</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm font-medium rounded-full">
                    {{ $dureeMoyenne }} jours
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Durée moyenne d'un emprunt
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">En Retard</h3>
                <span class="px-3 py-1 {{ $statsGlobales['emprunts_en_retard'] > 0 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }} text-sm font-medium rounded-full">
                    {{ $statsGlobales['emprunts_en_retard'] }}
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Emprunts actuellement en retard
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique Emprunts par Mois -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">📈 Emprunts par Mois (12 derniers mois)</h2>
            <div class="space-y-3">
                @php
                    $maxEmprunts = $empruntsParMois->max('total') ?: 1;
                @endphp
                @foreach($empruntsParMois as $mois)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $mois['mois'] }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $mois['total'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($mois['total'] / $maxEmprunts) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Répartition des Statuts -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">📊 Répartition des Statuts</h2>
            <div class="space-y-4">
                @php
                    $totalStatuts = array_sum($repartitionStatuts);
                    $couleurs = [
                        'en_attente' => ['bg' => 'bg-yellow-500', 'label' => 'En attente'],
                        'en_cours' => ['bg' => 'bg-blue-500', 'label' => 'En cours'],
                        'retourne' => ['bg' => 'bg-green-500', 'label' => 'Retournés'],
                        'en_retard' => ['bg' => 'bg-red-500', 'label' => 'En retard'],
                        'rejete' => ['bg' => 'bg-gray-500', 'label' => 'Rejetés'],
                    ];
                @endphp
                @foreach($repartitionStatuts as $statut => $count)
                    @php
                        $percentage = $totalStatuts > 0 ? round(($count / $totalStatuts) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $couleurs[$statut]['label'] }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $count }} ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="{{ $couleurs[$statut]['bg'] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top 10 Livres et Catégories -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top 10 Livres -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">🏆 Top 10 Livres les Plus Empruntés</h2>
            <div class="space-y-3">
                @foreach($topLivres as $index => $livre)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center flex-1">
                            <span class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $livre['titre'] }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $livre['auteurs'] }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full">
                            {{ $livre['total_emprunts'] }} emprunts
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top 5 Catégories -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">📚 Top 5 Catégories Populaires</h2>
            <div class="space-y-3">
                @foreach($categoriesPopulaires as $index => $categorie)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center flex-1">
                            <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $categorie['libelle'] }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $categorie['nombre_livres'] }} livre(s)</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs font-medium rounded-full">
                            {{ $categorie['total_emprunts'] }} emprunts
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Utilisateurs les Plus Actifs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">👥 Top 10 Lecteurs les Plus Actifs</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($utilisateursActifs as $index => $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center flex-1">
                        <span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $user['nom'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $user['login'] }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                        {{ $user['total_emprunts'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection