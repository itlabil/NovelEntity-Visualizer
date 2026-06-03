<div x-data="{ 
        /* Pastikan dropdown profil hanya terbuka otomatis jika URL spesifik milik profil */
        mobileProfilOpen: {{ (request()->is('web/vision-mission-history*') || request()->is('web/structure*') || request()->is('web/editorial-team*')) ? 'true' : 'false' }},
        mobileProkerOpen: {{ request()->is('web/majors*') ? 'true' : 'false' }},
        mobileInfoOpen: {{ request()->is('web/cek-lulus*') ? 'true' : 'false' }}
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
            <a href="/" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('/') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg font-medium transition-colors">
                <x-icons.home width="20" height="20" class="mr-3" />
                Home
            </a>

            <div>
                <button @click="mobileProfilOpen = !mobileProfilOpen" 
                        class="w-full flex items-center justify-between px-3 py-2 {{ (request()->is('web/vision-mission-history*') || request()->is('web/structure*') || request()->is('web/editorial-team*')) ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <div class="flex items-center">
                        <x-icons.category width="20" height="20" class="mr-3" />
                        Profil
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="mobileProfilOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="mobileProfilOpen" x-cloak
                     class="ml-8 mt-2 space-y-1 border-l-2 border-gray-100">
                    <a href="{{ url('/web/vision-mission-history') }}" class="block px-4 py-2 text-sm {{ request()->is('web/vision-mission-history*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-gray-900' }}">Visi, Misi & Sejarah</a>
                    <a href="{{ url('/web/structure') }}" class="block px-4 py-2 text-sm {{ request()->is('web/structure*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-gray-900' }}">Struktur</a>
                    <a href="{{ url('/web/editorial-team') }}" class="block px-4 py-2 text-sm {{ request()->is('web/editorial-team*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-gray-900' }}">Tim Redaksi</a>
                </div>
            </div>

            <a href="/web/article" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('web/article*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.files width="20" height="20" class="mr-3" />
                Artikel
            </a>

            <a href="/web/achievements" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('web/achievements*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.rocket width="20" height="20" class="mr-3" />
                Prestasi
            </a>

            <div>
                <button @click="mobileProkerOpen = !mobileProkerOpen" 
                        class="w-full flex items-center justify-between px-3 py-2 {{ request()->is('web/majors*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <div class="flex items-center">
                        <x-icons.arrows-sort width="20" height="20" class="mr-3" />
                        Program Keahlian
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="mobileProkerOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="mobileProkerOpen" x-cloak class="ml-8 mt-2 space-y-1 border-l-2 border-gray-100">
                    @php $menuMajors = \App\Models\Utama\Major::where('publish', 1)->orderBy('major')->get(); @endphp
                    @foreach($menuMajors as $major)
                        <a href="/web/majors/{{ $major->slug }}" 
                           class="block px-4 py-2 text-sm {{ request()->is('web/majors/' . $major->slug . '*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                            {{ $major->major }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="/web/albums" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('web/albums*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.photo width="20" height="20" class="mr-3" />
                Album
            </a>

            <div>
                <button @click="mobileInfoOpen = !mobileInfoOpen" 
                        class="w-full flex items-center justify-between px-3 py-2 {{ request()->is('web/cek-lulus*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <div class="flex items-center">
                        <x-icons.info-circle width="20" height="20" class="mr-3" />
                        Informasi
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="mobileInfoOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileInfoOpen" x-cloak class="ml-8 mt-2 space-y-1 border-l-2 border-gray-100">
                    <a href="{{ url('/web/cek-lulus') }}" class="block px-4 py-2 text-sm {{ request()->is('web/cek-lulus*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-gray-900' }}">Cek Kelulusan</a>
                </div>
            </div>

            <a href="/web/contact" @click="$store.mobileMenuOpen = false" 
               class="flex items-center px-3 py-2 {{ request()->is('web/contact*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg transition-colors">
                <x-icons.brand-whatsapp width="20" height="20" class="mr-3" />
                Kontak
            </a>
        </nav>
    </div>
</div>