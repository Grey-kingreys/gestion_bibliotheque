<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Lecteur\CatalogueController;
use App\Http\Controllers\Lecteur\EmpruntController as LecteurEmpruntController;

// ==========================================
// ROUTES PUBLIQUES (Catalogue accessible à tous)
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->isBibliothecaire()) {
            return redirect()->route('bibliothecaire.dashboard');
        }
        
        return redirect()->route('lecteur.catalogue');
    }
    
    return redirect()->route('catalogue');
})->name('home');

// Catalogue accessible à tous (même sans connexion)
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/livres/{livre}', [CatalogueController::class, 'show'])->name('livres.show');

// ==========================================
// AUTHENTIFICATION
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================================
// ROUTES LECTEUR
// ==========================================
Route::middleware(['auth', 'role:Rlecteur'])->prefix('lecteur')->name('lecteur.')->group(function () {
    // Catalogue (vue lecteur avec bouton emprunter)
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
    
    // Emprunts
    Route::post('/livres/{livre}/emprunter', [LecteurEmpruntController::class, 'demander'])->name('emprunter');
    Route::get('/mes-emprunts', [LecteurEmpruntController::class, 'mesEmprunts'])->name('mes-emprunts');
});

// ==========================================
// ROUTES BIBLIOTHÉCAIRE
// ==========================================
Route::middleware(['auth', 'role:Rbibliothecaire,Radmin'])->prefix('bibliothecaire')->name('bibliothecaire.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('bibliothecaire.dashboard');
    })->name('dashboard');
    
    // TODO: Ajouter les routes pour la gestion des livres, catégories, auteurs, emprunts
});

// ==========================================
// ROUTES ADMINISTRATEUR
// ==========================================
Route::middleware(['auth', 'role:Radmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // TODO: Ajouter les routes pour la gestion des utilisateurs
});

// ==========================================
// ROUTES COMMUNES (tous les utilisateurs authentifiés)
// ==========================================
Route::middleware('auth')->group(function () {
    // Profil utilisateur
    Route::get('/profil', function () {
        return view('profil.show');
    })->name('profile');
});