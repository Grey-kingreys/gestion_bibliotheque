<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Lecteur\CatalogueController;
use App\Http\Controllers\Lecteur\EmpruntController as LecteurEmpruntController;
use App\Http\Controllers\Bibliothecaire\LivreController;
use App\Http\Controllers\Bibliothecaire\CategorieController;
use App\Http\Controllers\Bibliothecaire\AuteurController;
use App\Http\Controllers\Bibliothecaire\EmpruntController as BiblioEmpruntController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatistiqueController;

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
    
    // Gestion des livres
    Route::resource('livres', LivreController::class);
    Route::post('livres/{livre}/toggle', [LivreController::class, 'toggleDisponibilite'])->name('livres.toggle');
    
    // Gestion des catégories
    Route::resource('categories', CategorieController::class);
    
    // Gestion des auteurs
    Route::resource('auteurs', AuteurController::class);
    
    // Gestion des emprunts
    Route::get('emprunts', [BiblioEmpruntController::class, 'index'])->name('emprunts.index');
    Route::post('emprunts/{emprunt}/valider', [BiblioEmpruntController::class, 'valider'])->name('emprunts.valider');
    Route::post('emprunts/{emprunt}/retour', [BiblioEmpruntController::class, 'retour'])->name('emprunts.retour');
    Route::post('emprunts/{emprunt}/rejeter', [BiblioEmpruntController::class, 'rejeter'])->name('emprunts.rejeter');
});

// ==========================================
// ROUTES ADMINISTRATEUR
// ==========================================
Route::middleware(['auth', 'role:Radmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StatistiqueController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
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