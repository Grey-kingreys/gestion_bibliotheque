<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800" x-data="{ open: false, userDropdown: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et Nom -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">BiblioApp</span>
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Inscription
                    </a>
                @else
                    <!-- Menu selon le rôle -->
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Tableau de bord
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Utilisateurs
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Livres
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Statistiques
                        </a>
                    @elseif(auth()->user()->isBibliothecaire())
                        <a href="{{ route('bibliothecaire.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Tableau de bord
                        </a>
                        <a href="{{ route('bibliothecaire.livres.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Livres
                        </a>
                        <a href="{{ route('bibliothecaire.emprunts.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Emprunts
                        </a>
                        <a href="{{ route('bibliothecaire.categories.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Catégories
                        </a>
                    @else
                        <a href="{{ route('lecteur.catalogue') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Catalogue
                        </a>
                        <a href="{{ route('lecteur.mes-emprunts') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Mes Emprunts
                        </a>
                    @endif

                    <!-- Dropdown Utilisateur -->
                    <div class="relative">
                        <button @click="userDropdown = !userDropdown" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->prenom }}</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': userDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="userDropdown" 
                             @click.away="userDropdown = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700"
                             style="display: none;">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mon Profil
                                </div>
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Bouton Menu Mobile -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="md:hidden border-t border-gray-200 dark:border-gray-800"
         style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                    Inscription
                </a>
            @else
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Tableau de bord
                    </a>
                @elseif(auth()->user()->isBibliothecaire())
                    <a href="{{ route('bibliothecaire.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('lecteur.catalogue') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Catalogue
                    </a>
                @endif
                
                <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                    <a href="{{ route('profile') }}" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Mon Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Alpine.js CDN (à ajouter dans le head du layout principal) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>