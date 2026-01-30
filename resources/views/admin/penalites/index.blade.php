{{-- resources/views/admin/penalites/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                💰 Gestion des Pénalités
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $penalites->total() }} pénalité(s) au total
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.penalites.par-utilisateur') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Par Utilisateur
            </a>
            <a href="{{ route('admin.penalites.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter CSV
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Pénalités</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_penalites'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mb-1">Montant Total</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-blue-500 dark:text-blue-400 mt-1">GNF</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 dark:text-green-300 mb-1">Payées</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['montant_paye'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-green-500 dark:text-green-400 mt-1">GNF</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-1">Impayées</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['montant_impaye'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $stats['non_payees'] }} pénalité(s)</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.penalites.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Rechercher (nom, login)..."
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
            >
            
            <select 
                name="payee"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">Tous les statuts</option>
                <option value="0" {{ request('payee') === '0' ? 'selected' : '' }}>Non payées</option>
                <option value="1" {{ request('payee') === '1' ? 'selected' : '' }}>Payées</option>
            </select>

            <select 
                name="sort"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
            >
                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                <option value="montant" {{ request('sort') === 'montant' ? 'selected' : '' }}>Montant</option>
                <option value="jours_retard" {{ request('sort') === 'jours_retard' ? 'selected' : '' }}>Jours de retard</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Rechercher
                </button>
                <a href="{{ route('admin.penalites.index') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition">
                    ✖
                </a>
            </div>
        </form>
    </div>

    <!-- Messages -->
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

    <!-- Liste des pénalités -->
    <div class="space-y-4">
        @forelse($penalites as $penalite)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Informations -->
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <!-- Avatar utilisateur -->
                            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                {{ strtoupper(substr($penalite->emprunt->user->prenom, 0, 1)) }}{{ strtoupper(substr($penalite->emprunt->user->nom, 0, 1)) }}
                            </div>

                            <div class="flex-1">
                                <!-- Utilisateur -->
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $penalite->emprunt->user->full_name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $penalite->emprunt->user->login }} • {{ $penalite->emprunt->user->email }}
                                </p>

                                <!-- Livre -->
                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                    📖 {{ $penalite->emprunt->livre->titre }}
                                </p>

                                <!-- Détails -->
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                                    <span>Retard: {{ $penalite->jours_retard }} jour(s)</span>
                                    <span>•</span>
                                    <span>Créée le: {{ $penalite->created_at->format('d/m/Y à H:i') }}</span>
                                    @if($penalite->payee && $penalite->date_paiement)
                                        <span>•</span>
                                        <span>Payée le: {{ $penalite->date_paiement->format('d/m/Y à H:i') }}</span>
                                    @endif
                                </div>

                                <!-- Motif -->
                                @if($penalite->motif)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 italic">
                                        "{{ $penalite->motif }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Montant et Actions -->
                    <div class="flex flex-col items-end gap-3">
                        <!-- Montant -->
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($penalite->montant, 0, ',', ' ') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">GNF</p>
                        </div>

                        <!-- Statut -->
                        <span class="px-4 py-2 rounded-full text-sm font-medium
                            {{ $penalite->payee ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $penalite->payee ? '✓ Payée' : '⚠ Non payée' }}
                        </span>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a 
                                href="{{ route('admin.penalites.show', $penalite) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                            >
                                Détails
                            </a>

                            @if(!$penalite->payee)
                                <form method="POST" action="{{ route('admin.penalites.marquer-payee', $penalite) }}" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Marquer payée
                                    </button>
                                </form>

                                <form 
                                    method="POST" 
                                    action="{{ route('admin.penalites.annuler', $penalite) }}"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette pénalité ?');"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune pénalité</h3>
                <p class="text-gray-600 dark:text-gray-400">Aucune pénalité ne correspond à vos critères</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $penalites->links() }}
    </div>
</div>
@endsection