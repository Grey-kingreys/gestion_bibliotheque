{{-- resources/views/admin/penalites/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('admin.penalites.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Carte de pénalité -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden sticky top-6">
                <!-- Header avec montant -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 p-8 text-white text-center">
                    <svg class="mx-auto w-16 h-16 mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-100 text-sm mb-2">Montant de la pénalité</p>
                    <p class="text-5xl font-bold">{{ number_format($penalite->montant, 0, ',', ' ') }}</p>
                    <p class="text-red-100 text-lg mt-2">GNF</p>
                </div>

                <div class="p-6">
                    <!-- Statut -->
                    <div class="mb-6 p-4 rounded-lg text-center
                        {{ $penalite->payee ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                        <span class="text-lg font-bold
                            {{ $penalite->payee ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                            {{ $penalite->payee ? '✓ Pénalité Payée' : '⚠ Pénalité Non Payée' }}
                        </span>
                    </div>

                    <!-- Informations clés -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Jours de retard</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $penalite->jours_retard }} jour(s)</p>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date de création</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $penalite->created_at->format('d/m/Y à H:i') }}</p>
                        </div>

                        @if($penalite->payee && $penalite->date_paiement)
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date de paiement</p>
                                <p class="font-medium text-green-600 dark:text-green-400">{{ $penalite->date_paiement->format('d/m/Y à H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 space-y-3">
                        @if(!$penalite->payee)
                            <form method="POST" action="{{ route('admin.penalites.marquer-payee', $penalite) }}">
                                @csrf
                                <button 
                                    type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Marquer comme payée
                                </button>
                            </form>

                            <form 
                                method="POST" 
                                action="{{ route('admin.penalites.annuler', $penalite) }}"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette pénalité ?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Annuler la pénalité
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Détails -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations sur l'utilisateur -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informations sur l'utilisateur
                </h2>

                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xl flex-shrink-0">
                        {{ strtoupper(substr($penalite->emprunt->user->prenom, 0, 1)) }}{{ strtoupper(substr($penalite->emprunt->user->nom, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $penalite->emprunt->user->full_name }}
                        </h3>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Login:</span> {{ $penalite->emprunt->user->login }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Email:</span> {{ $penalite->emprunt->user->email }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Téléphone:</span> {{ $penalite->emprunt->user->telephone }}
                            </p>
                        </div>
                    </div>
                    <a 
                        href="{{ route('admin.users.show', $penalite->emprunt->user) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                    >
                        Voir le profil
                    </a>
                </div>
            </div>

            <!-- Informations sur l'emprunt -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Informations sur l'emprunt
                </h2>

                <div class="space-y-4">
                    <!-- Livre -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">
                            {{ $penalite->emprunt->livre->titre }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Par {{ $penalite->emprunt->livre->auteurs->pluck('full_name')->implode(', ') }}
                        </p>
                        <span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full mt-2">
                            {{ $penalite->emprunt->livre->categorie->libelle }}
                        </span>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date d'emprunt</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $penalite->emprunt->date_emprunt->format('d/m/Y') }}</p>
                        </div>

                        <div class="border-l-4 border-yellow-500 pl-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date de retour prévue</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $penalite->emprunt->date_retour_prevue->format('d/m/Y') }}</p>
                        </div>

                        <div class="border-l-4 border-{{ $penalite->emprunt->date_retour_effective ? 'green' : 'red' }}-500 pl-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date de retour effective</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $penalite->emprunt->date_retour_effective ? $penalite->emprunt->date_retour_effective->format('d/m/Y') : 'Non retourné' }}
                            </p>
                        </div>

                        <div class="border-l-4 border-red-500 pl-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Retard</p>
                            <p class="font-medium text-red-600 dark:text-red-400">{{ $penalite->jours_retard }} jour(s)</p>
                        </div>
                    </div>

                    <!-- Statut de l'emprunt -->
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Statut de l'emprunt</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($penalite->emprunt->statut === 'retourne') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($penalite->emprunt->statut === 'en_retard') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $penalite->emprunt->statut)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Motif de la pénalité -->
            @if($penalite->motif)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Motif de la pénalité
                    </h3>
                    <p class="text-yellow-700 dark:text-yellow-400 italic">"{{ $penalite->motif }}"</p>
                </div>
            @endif

            <!-- Calcul de la pénalité -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Calcul de la pénalité
                </h3>
                <div class="space-y-2 text-blue-700 dark:text-blue-400">
                    <p>Taux: <span class="font-bold">500 GNF par jour de retard</span></p>
                    <p>Nombre de jours de retard: <span class="font-bold">{{ $penalite->jours_retard }} jour(s)</span></p>
                    <div class="border-t border-blue-300 dark:border-blue-700 pt-2 mt-2">
                        <p class="text-lg">
                            Montant total: <span class="font-bold text-xl">{{ $penalite->jours_retard }} × 500 = {{ number_format($penalite->montant, 0, ',', ' ') }} GNF</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection