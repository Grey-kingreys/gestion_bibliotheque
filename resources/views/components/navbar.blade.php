<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et Nom -->
            <div class="flex items-center">
                <a href="#" class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">BiblioApp</span>
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                @guest
                    <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition">
                        Catalogue
                    </a>
                    <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium transition">
                        Connexion
                    </a>
                @else
                    <!-- Menu selon le rôle -->
                    @if(auth()->user()->role === 'Radmin')
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Tableau de bord
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Utilisateurs
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Livres
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Statistiques
                        </a>
                    @elseif(auth()->user()->role === 'Rbibliothecaire')
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Tableau de bord
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Livres
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Emprunts
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Catégories
                        </a>
                    @else
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Catalogue
                        </a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            Mes Emprunts
                        </a>
                    @endif

                    <!-- Dropdown Utilisateur -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-indigo-600 px-3 py-2 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ auth()->user()->login }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Bouton Menu Mobile -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div x-show="mobileMenu" class="md:hidden border-t border-gray-200 dark:border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @guest
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                    Catalogue
                </a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                    Connexion
                </a>
            @else
                @if(auth()->user()->role === 'Radmin')
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Tableau de bord
                    </a>
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Utilisateurs
                    </a>
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Livres
                    </a>
                @elseif(auth()->user()->role === 'Rbibliothecaire')
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Tableau de bord
                    </a>
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Livres
                    </a>
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Emprunts
                    </a>
                @else
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Catalogue
                    </a>
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Mes Emprunts
                    </a>
                @endif
                
                <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                    <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                        Mon Profil
                    </a>
                    <form method="POST" action="#">
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

<!-- Script Alpine.js pour les dropdowns -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbar', () => ({
            mobileMenu: false
        }))
    })
</script>