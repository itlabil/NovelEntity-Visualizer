<div x-data="{ 
        /* Pastikan dropdown profil hanya terbuka otomatis jika URL spesifik milik profil */
        mobileProfilOpen: {{ (request()->is('regis/vision-mission-history*') || request()->is('regis/structure*') || request()->is('regis/editorial-team*')) ? 'true' : 'false' }},
        mobileProkerOpen: {{ request()->is('regis/majors*') ? 'true' : 'false' }},
        mobileInfoOpen: {{ request()->is('regis/cek-lulus*') ? 'true' : 'false' }}
    }">
    
    <div x-show="$store.mobileMenuOpen" 
         x-transition:opacity
         class="fixed inset-0 z-40 bg-black/50 lg:hidden" 
         @click="$store.mobileMenuOpen = false"></div>

    <div x-show="$store.mobileMenuOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl lg:hidden overflow-y-auto">
        
        <div class="flex items-center px-4 py-4 shadow-sm border-b border-gray-100 bg-white">
            <div class="flex items-center flex-1">
                <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center overflow-hidden">
                    </div>
                <span class="ml-2 text-lg font-bold text-gray-900 text-left flex-1">SMK PELITA</span>
            </div>
            <button @click="$store.mobileMenuOpen = false" class="p-2 ml-2 rounded-md text-gray-400 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="px-4 py-6 space-y-2">
            <a href="/regis/dashboard" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/dashboard') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg font-medium transition-colors">
                <x-icons.home width="20" height="20" class="mr-3" />
                Home
            </a>

            <a href="/regis/register" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/article*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.pencil width="20" height="20" class="mr-3" />
                Daftar
            </a>

            <a href="/regis/check-regis" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/check-regis*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.circle-check width="20" height="20" class="mr-3" />
                Cek Daftar
            </a>

            <a href="/regis/status" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/status*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.category width="20" height="20" class="mr-3" />
                Cek Status
            </a>

            <a href="/regis/mpls" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/mpls*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.rocket width="20" height="20" class="mr-3" />
                MPLS
            </a>

            <a href="/regis/contact" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('regis/contact*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.brand-whatsapp width="20" height="20" class="mr-3" />
                Kontak
            </a>
        </nav>
    </div>
</div>