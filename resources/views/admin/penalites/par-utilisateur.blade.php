{{-- resources/views/admin/penalites/par-utilisateur.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                👥 Pénalités par Utilisateur
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $users->total() }} utilisateur(s) avec pénalité(s)
            </p>
        </div>
        <a href="{{ route('admin.penalites.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Vue Liste
        </a>
    </div>

    <!-- Filtre -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.penalites.par-utilisateur') }}" class="flex gap-4">
            <select 
                name="statut"
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                onchange="this.form.submit()"
            >
                <option value="">Toutes les pénalités</option>
                <option value="impayees" {{ request('statut') === 'impayees' ? 'selected' : '' }}>Uniquement impayées</option>
            </select>
            
            <a 
                href="{{ route('admin.penalites.par-utilisateur') }}"
                class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-medium transition"
            >
                Réinitialiser
            </a>
        </form>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="space-y-6">
        @forelse($users as $user)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <!-- En-tête utilisateur -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-indigo-600 font-semibold text-xl">
                                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                            </div>

                            <!-- Infos -->
                            <div class="text-white">
                                <h3 class="text-2xl font-bold mb-1">{{ $user->full_name }}</h3>
                                <p class="text-indigo-100">{{ $user->login }} • {{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Totaux -->
                        <div class="text-right text-white">
                            <p class="text-indigo-100 text-sm mb-1">Total des pénalités</p>
                            <p class="text-4xl font-bold">{{ number_format($user->total_penalites, 0, ',', ' ') }}</p>
                            <p class="text-indigo-100 text-sm mt-1">GNF</p>
                            
                            @if($user->penalites_impayees > 0)
                                <p class="text-red-200 text-sm mt-2">
                                    <span class="font-semibold">{{ number_format($user->penalites_impayees, 0, ',', ' ') }} GNF</span> impayées
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Liste des pénalités de l'utilisateur -->
                <div class="p-6">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                        Historique des pénalités ({{ $user->penalites_count }})
                    </h4>

                    <div class="space-y-3">
                        @foreach($user->emprunts->pluck('penalite')->whereNotNull()->sortByDesc('created_at') as $penalite)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <!-- Livre -->
                                    <p class="font-medium text-gray-900 dark:text-white mb-1">
                                        {{ $penalite->emprunt->livre->titre }}
                                    </p>

                                    <!-- Détails -->
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600 dark:text-gray-400">
                                        <span>{{ $penalite->jours_retard }} jour(s) de retard</span>
                                        <span>•</span>
                                        <span>Créée le {{ $penalite->created_at->format('d/m/Y') }}</span>
                                        @if($penalite->payee)
                                            <span>•</span>
                                            <span class="text-green-600 dark:text-green-400 font-medium">
                                                Payée le {{ $penalite->date_paiement->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <!-- Montant -->
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ number_format($penalite->montant, 0, ',', ' ') }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">GNF</p>
                                    </div>

                                    <!-- Statut -->
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $penalite->payee ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $penalite->payee ? '✓ Payée' : '⚠ Impayée' }}
                                    </span>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        <a 
                                            href="{{ route('admin.penalites.show', $penalite) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition"
                                        >
                                            Détails
                                        </a>

                                        @if(!$penalite->payee)
                                            <form method="POST" action="{{ route('admin.penalites.marquer-payee', $penalite) }}" class="inline">
                                                @csrf
                                                <button 
                                                    type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition"
                                                >
                                                    Payer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bouton voir profil -->
                    <div class="mt-4">
                        <a 
                            href="{{ route('admin.users.show', $user) }}"
                            class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 text-sm font-medium"
                        >
                            Voir le profil complet de l'utilisateur →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun utilisateur avec pénalité</h3>
                <p class="text-gray-600 dark:text-gray-400">Aucun utilisateur ne correspond à vos critères</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection