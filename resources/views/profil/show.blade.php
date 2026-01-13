<!-- ============================================= -->

<!-- resources/views/profil/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
            Mon Profil
        </h1>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ auth()->user()->login }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ auth()->user()->nom }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ auth()->user()->prenom }}</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ auth()->user()->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphone</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ auth()->user()->telephone }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rôle</label>
                <p class="mt-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                        @if(auth()->user()->isAdmin())
                            Administrateur
                        @elseif(auth()->user()->isBibliothecaire())
                            Bibliothécaire
                        @else
                            Lecteur
                        @endif
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection