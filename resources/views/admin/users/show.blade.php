{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne gauche - Profil -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden sticky top-6">
                <!-- Avatar -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-48 flex items-center justify-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-indigo-600">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                    </div>
                </div>

                <div class="p-6">
                    <!-- Nom complet -->
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-2">
                        {{ $user->full_name }}
                    </h2>
                    
                    <!-- Login -->
                    <p class="text-center text-gray-600 dark:text-gray-400 mb-4">{{ $user->login }}</p>

                    <!-- Rôle -->
                    <div class="flex justify-center mb-6">
                        <span class="px-4 py-2 rounded-full text-sm font-medium
                            @if($user->isAdmin()) bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($user->isBibliothecaire()) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                            @endif">
                            @if($user->isAdmin()) 🛡️ Administrateur
                            @elseif($user->isBibliothecaire()) 📚 Bibliothécaire
                            @else 📖 Lecteur
                            @endif
                        </span>
                    </div>

                    <!-- Statut -->
                    <div class="mb-6 p-4 rounded-lg text-center
                        {{ $user->actif ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                        @if($user->actif)
                            <span class="text-green-700 dark:text-green-400 font-medium">✓ Compte actif</span>
                        @else
                            <span class="text-red-700 dark:text-red-400 font-medium">✗ Compte désactivé</span>
                        @endif
                    </div>

                    <!-- Boutons d'action -->
                    <div class="space-y-3">
                        <a 
                            href="{{ route('admin.users.edit', $user) }}"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('admin.users.toggle-actif', $user) }}">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ $user->actif ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>

                    <!-- Informations de contact -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3 text-sm">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="break-all">{{ $user->email }}</span>
                        </div>
                        
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $user->telephone }}</span>
                        </div>

                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Activités -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistiques des emprunts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques d'emprunt
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <p class="text-sm text-purple-600 dark:text-purple-400 mb-1">Total</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['total_emprunts'] }}</p>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">En cours</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['en_cours'] }}</p>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                        <p class="text-sm text-red-600 dark:text-red-400 mb-1">En retard</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['en_retard'] }}</p>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <p class="text-sm text-green-600 dark:text-green-400 mb-1">Retournés</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['retournes'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Emprunts en cours -->
            @if($user->emprunts->whereIn('statut', ['en_cours', 'en_retard', 'en_attente'])->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Emprunts actifs
                    </h2>

                    <div class="space-y-4">
                        @foreach($user->emprunts->whereIn('statut', ['en_cours', 'en_retard', 'en_attente'])->sortByDesc('created_at') as $emprunt)
                            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="w-16 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $emprunt->livre->titre }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $emprunt->livre->auteurs->pluck('full_name')->implode(', ') }}
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

                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($emprunt->statut === 'en_attente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($emprunt->estEnRetard()) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @endif">
                                    @if($emprunt->statut === 'en_attente') En attente
                                    @elseif($emprunt->estEnRetard()) Retard: {{ $emprunt->joursDeRetard() }}j
                                    @else En cours
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Historique des emprunts -->
            @if($user->emprunts->where('statut', 'retourne')->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Historique des retours ({{ $user->emprunts->where('statut', 'retourne')->count() }} au total)
                    </h2>

                    <div class="space-y-2">
                        @foreach($user->emprunts->where('statut', 'retourne')->sortByDesc('date_retour_effective')->take(10) as $emprunt)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $emprunt->livre->titre }}</span>
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

                        @if($user->emprunts->where('statut', 'retourne')->count() > 10)
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                                Et {{ $user->emprunts->where('statut', 'retourne')->count() - 10 }} autre(s) emprunt(s) retourné(s)
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Si aucun emprunt -->
            @if($user->emprunts->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                    <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun emprunt</h3>
                    <p class="text-gray-600 dark:text-gray-400">Cet utilisateur n'a jamais emprunté de livre</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection