<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Page d'accueil (redirection)
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
    
    return redirect()->route('login');
})->name('home');

// Routes d'authentification (invités uniquement)
Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Inscription
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Déconnexion (authentifié uniquement)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    
    // ==========================================
    // ROUTES ADMINISTRATEUR
    // ==========================================
    Route::middleware('role:Radmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Gestion des utilisateurs
        // Route::resource('users', UserController::class);
        
        // Gestion des livres
        // Route::resource('livres', LivreController::class);
        
        // Statistiques
        // Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques');
    });

    // ==========================================
    // ROUTES BIBLIOTHÉCAIRE
    // ==========================================
    Route::middleware('role:Rbibliothecaire')->prefix('bibliothecaire')->name('bibliothecaire.')->group(function () {
        Route::get('/dashboard', function () {
            return view('bibliothecaire.dashboard');
        })->name('dashboard');
        
        // Gestion des livres
        // Route::resource('livres', LivreController::class);
        
        // Gestion des emprunts
        // Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
        // Route::post('/emprunts/{emprunt}/valider', [EmpruntController::class, 'valider'])->name('emprunts.valider');
        // Route::post('/emprunts/{emprunt}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
        
        // Gestion des catégories et auteurs
        // Route::resource('categories', CategorieController::class);
        // Route::resource('auteurs', AuteurController::class);
    });

    // ==========================================
    // ROUTES LECTEUR
    // ==========================================
    Route::middleware('role:Rlecteur')->prefix('lecteur')->name('lecteur.')->group(function () {
        Route::get('/catalogue', function () {
            return view('lecteur.catalogue');
        })->name('catalogue');
        
        // Mes emprunts
        // Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('mes-emprunts');
        
        // Demande d'emprunt
        // Route::post('/livres/{livre}/emprunter', [EmpruntController::class, 'demander'])->name('emprunter');
    });

    // ==========================================
    // ROUTES COMMUNES (tous les utilisateurs authentifiés)
    // ==========================================
    
    // Profil utilisateur
    Route::get('/profil', function () {
        return view('profil.show');
    })->name('profile');
    
    // Recherche de livres (accessible à tous)
    // Route::get('/catalogue', [LivreController::class, 'catalogue'])->name('catalogue');
    // Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');
});