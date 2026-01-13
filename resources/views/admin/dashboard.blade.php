<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            Dashboard Administrateur
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Bienvenue {{ auth()->user()->full_name }} ! Vous êtes connecté en tant qu'administrateur.
        </p>
    </div>
</div>
@endsection



