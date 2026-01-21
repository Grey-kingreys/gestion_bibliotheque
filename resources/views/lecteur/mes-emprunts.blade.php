{{-- resources/views/lecteur/mes-emprunts.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            📚 Mes Emprunts
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Gérez vos emprunts de livres
        </p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Emprunts en cours</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['en_cours'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">En retard</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['en_retard'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total emprunts</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('lecteur.mes-emprunts') }}" class="flex gap-4">
            <select 
                name="statut"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                onchange="this.form.submit()"
            >
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="retourne" {{ request('statut') === 'retourne' ? 'selected' : '' }}>Retournés</option>
                <option value="en_retard" {{ request('statut') === 'en_retard' ? 'selected' : '' }}>En retard</option>
            </select>
            
            <a 
                href="{{ route('lecteur.mes-emprunts') }}"
                class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg font-medium transition"
            >
                Réinitialiser
            </a>
        </form>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Liste des emprunts -->
    @if($emprunts->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun emprunt</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Vous n'avez pas encore emprunté de livres</p>
            <a 
                href="{{ route('lecteur.catalogue') }}"
                class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Parcourir le catalogue
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($emprunts as $emprunt)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Informations du livre -->
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <!-- Image placeholder -->
                                    <div class="w-20 h-28 bg-gradient-to-br from-indigo-500 to-purple-600 rounded flex items-center justify-center flex-shrink-0">
                                        <svg class="w-10 h-10 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $emprunt->livre->titre }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            Par {{ $emprunt->livre->auteurs->pluck('full_name')->implode(', ') }}
                                        </p>
                                        <span class="inline-block px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded">
                                            {{ $emprunt->livre->categorie->libelle }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dates et statut -->
                            <div class="flex flex-col items-end gap-2">
                                <!-- Statut -->
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($emprunt->statut === 'en_attente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($emprunt->statut === 'valide') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($emprunt->statut === 'en_cours') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($emprunt->statut === 'retourne') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    @if($emprunt->statut === 'en_attente') 🕐 En attente
                                    @elseif($emprunt->statut === 'valide') ✅ Validé
                                    @elseif($emprunt->statut === 'en_cours') 📖 En cours
                                    @elseif($emprunt->statut === 'retourne') ✔️ Retourné
                                    @else ⚠️ En retard
                                    @endif
                                </span>

                                <!-- Dates -->
                                <div class="text-right text-sm space-y-1">
                                    <div class="text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Emprunté:</span> {{ $emprunt->date_emprunt->format('d/m/Y') }}
                                    </div>
                                    <div class="text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Retour prévu:</span> 
                                        <span class="{{ $emprunt->estEnRetard() ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @if($emprunt->date_retour_effective)
                                        <div class="text-green-600 dark:text-green-400">
                                            <span class="font-medium">Retourné:</span> {{ $emprunt->date_retour_effective->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Alerte retard -->
                                @if($emprunt->estEnRetard())
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded px-3 py-2 text-xs">
                                        <span class="text-red-700 dark:text-red-400 font-medium">
                                            ⚠️ Retard de {{ $emprunt->joursDeRetard() }} jour(s)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Commentaire -->
                        @if($emprunt->commentaire)
                            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <span class="font-medium">Note:</span> {{ $emprunt->commentaire }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $emprunts->links() }}
        </div>
    @endif
</div>
@endsection