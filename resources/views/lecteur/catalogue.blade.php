
<!-- resources/views/lecteur/catalogue.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            Catalogue des Livres
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Bienvenue {{ auth()->user()->full_name }} ! Consultez notre catalogue de livres.
        </p>
    </div>
</div>
@endsection