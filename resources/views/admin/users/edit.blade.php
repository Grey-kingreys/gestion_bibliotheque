{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            ✏️ Modifier l'utilisateur
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Login : {{ $user->login }}</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700 dark:text-red-400">✗ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nom et Prénom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nom" 
                        value="{{ old('nom', $user->nom) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="prenom" 
                        value="{{ old('prenom', $user->prenom) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <!-- Téléphone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Téléphone <span class="text-red-500">*</span>
                </label>
                <input 
                    type="tel" 
                    name="telephone" 
                    value="{{ old('telephone', $user->telephone) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <!-- Rôle -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Rôle <span class="text-red-500">*</span>
                </label>
                <select 
                    name="role"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="Radmin" {{ old('role', $user->role) === 'Radmin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="Rbibliothecaire" {{ old('role', $user->role) === 'Rbibliothecaire' ? 'selected' : '' }}>Bibliothécaire</option>
                    <option value="Rlecteur" {{ old('role', $user->role) === 'Rlecteur' ? 'selected' : '' }}>Lecteur</option>
                </select>
            </div>

            <!-- Changement de mot de passe (optionnel) -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Changer le mot de passe (optionnel)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nouveau mot de passe
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                            placeholder="Laisser vide pour ne pas changer"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirmer le mot de passe
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                            placeholder="Confirmer le nouveau mot de passe"
                        >
                    </div>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Laissez vide si vous ne souhaitez pas modifier le mot de passe
                </p>
            </div>

            <!-- Statut actif -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="actif" 
                    id="actif"
                    {{ old('actif', $user->actif) ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                >
                <label for="actif" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Compte actif
                </label>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition"
                >
                    ✓ Mettre à jour
                </button>
                <a 
                    href="{{ route('admin.users.index') }}"
                    class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg font-medium text-center transition"
                >
                    Annuler
                </a>
            </div>
        </form>

        <!-- Zone de danger -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-4">
                ⚠️ Zone de danger
            </h3>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-red-800 dark:text-red-300">Supprimer cet utilisateur</h4>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                            Cette action désactivera définitivement le compte. Les emprunts en cours empêchent la suppression.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                        >
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection