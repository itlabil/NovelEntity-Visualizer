<!-- Top Navigation -->
<nav
    x-data="{ 
        userDropdownOpen: false,
        masterDropdownOpen: false,
    }"
    class="bg-white shadow sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button @click="$store.mobileMenuOpen = true" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </button>

                <a href="{{ route('account.dashboard.index') }}">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('images/icon-logo.svg') }}" alt="NOVELENTITY" class="w-48 h-auto">
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">NovEnVis</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links with Icons -->
                <div class="hidden lg:ml-10 lg:flex lg:space-x-3 ">


                    <a href="/account/dashboard" class="{{ request()->is('account/dashboard*') ? 'text-primary' : '' }} text-gray-500 px-1 pt-2 pb-2 text-sm font-medium flex items-center space-x-2">
                        <x-icons.home width="20" height="20" />
                        <span>Dashboard</span>
                    </a>

                    @if(auth()->user()->can('users.index') || 
                        auth()->user()->can('roles.index') || 
                        auth()->user()->can('permissions.index')
                        )
                        <!-- Desktop Master Dropdown -->
                        <div class="relative" @click.away="masterDropdownOpen = false">
                            <button @click="masterDropdownOpen = !masterDropdownOpen" class="cursor-pointer {{ 
                                    request()->is('account/users*') || 
                                    request()->is('account/roles*') || 
                                    request()->is('account/permissions*') ? 'text-primary' : '' 
                                }} text-gray-500 hover:text-gray-700 px-1 pt-2 pb-2 text-sm font-medium transition-colors flex items-center space-x-2">
                                <x-icons.box-seam width="20" height="20" />
                                <span>Master Data</span>
                                <svg class="w-4 h-4 transition-transform" :class="masterDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="masterDropdownOpen" x-cloak x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">

                                @can('roles.index')
                                    <a href="/account/roles" class="block px-4 py-2 text-sm {{ request()->is('account/roles*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Roles</a>
                                @endcan

                                @can('permissions.index')
                                    <a href="/account/permissions" class="block px-4 py-2 text-sm {{ request()->is('account/permissions*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Permissions</a>
                                @endcan

                                @can('users.index')
                                    <a href="/account/users" class="block px-4 py-2 text-sm {{ request()->is('account/users*') ? 'text-primary' : '' }} text-gray-700 hover:bg-gray-100 hover:rounded-xl">Users</a>
                                @endcan
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="flex items-center space-x-4">

                <!-- User Dropdown -->
                <div class="relative" @click.away="userDropdownOpen = false">
                    <button @click="userDropdownOpen = !userDropdownOpen" class="flex cursor-pointer items-center space-x-3 p-1 rounded-lg ">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->join(', ') }}</p>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="userDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div x-show="userDropdownOpen" x-cloak x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl border border-gray-200 py-1 z-50 p-2">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('logout') }}" style="cursor: pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:rounded-xl">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
