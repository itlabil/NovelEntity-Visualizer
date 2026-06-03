@extends('layouts.account')

@section('title', 'Dashboard Overview')

@section('content')

<!-- HEADER & GREETING CENTER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 border-b border-slate-100 pb-5">
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Halo, {{ auth()->user()->name }} 👋</h2>
        <p class="text-sm text-slate-500 mt-1">Pantau pertumbuhan pustaka kata kunci terjemahan novel dan antrean moderasi secara real-time.</p>
    </div>
    @can('admin')
        <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-100 shadow-xs">
            <x-icons.eye width="16" height="16" class="text-blue-600" />
            <span>Mode Administrator Aktif</span>
        </div>
    @endcan
</div>

<!-- SECTION 1: CORE STATS GRID (400x400px Vibe Harmony) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- Card 1: Total Pustaka Novel -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Katalog Novel</p>
                <p class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalNovels }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 group-hover:bg-orange-100 rounded-xl flex items-center justify-center border border-orange-100/50 transition-colors">
                <x-icons.book width="22" height="22" class="text-orange-600" />
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
            <span>Disetujui: <strong class="text-green-600">{{ $approvedNovels }}</strong></span>
            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
        </div>
    </div>

    <!-- Card 2: Total Node Entitas Kata Kunci -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Entitas</p>
                <p class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalEntities }}</p>
            </div>
            <div class="w-12 h-12 bg-sky-50 group-hover:bg-sky-100 rounded-xl flex items-center justify-center border border-sky-100/50 transition-colors">
                <!-- Gunakan icon eye/visualizer bawaan komponen kamu -->
                <x-icons.eye width="22" height="22" class="text-sky-600" />
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-50 text-xs text-slate-400">
            <span>Tersebar di berbagai katalog novel</span>
        </div>
    </div>

    <!-- Card 3: Antrean Review Tertunda (Sistem Peringatan) -->
    <div class="p-5 rounded-xl border {{ ($pendingNovels + $pendingEntities) > 0 ? 'bg-amber-50/40 border-amber-200' : 'bg-white border-slate-200' }} shadow-xs hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold {{ ($pendingNovels + $pendingEntities) > 0 ? 'text-amber-800' : 'text-slate-400' }} uppercase tracking-wider">Butuh Review</p>
                <p class="text-3xl font-black {{ ($pendingNovels + $pendingEntities) > 0 ? 'text-amber-700' : 'text-slate-900' }} tracking-tight">
                    {{ $pendingNovels + $pendingEntities }}
                </p>
            </div>
            <div class="w-12 h-12 {{ ($pendingNovels + $pendingEntities) > 0 ? 'bg-amber-100 animate-pulse' : 'bg-slate-50' }} rounded-xl flex items-center justify-center border border-amber-200/50">
                <x-icons.article width="22" height="22" class="{{ ($pendingNovels + $pendingEntities) > 0 ? 'text-amber-700' : 'text-slate-400' }}" />
            </div>
        </div>
        <div class="mt-4 pt-3 border-t {{ ($pendingNovels + $pendingEntities) > 0 ? 'border-amber-200/40' : 'border-slate-50' }} text-xs {{ ($pendingNovels + $pendingEntities) > 0 ? 'text-amber-800 font-medium' : 'text-slate-400' }}">
            <span>Novel: {{ $pendingNovels }} • Entitas: {{ $pendingEntities }}</span>
        </div>
    </div>

    <!-- Card 4: Kontributor Aktif / Identitas Pengguna -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hak Akses Role</p>
                <p class="text-lg font-black text-slate-800 tracking-tight uppercase mt-1 truncate max-w-[140px]">
                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-50 group-hover:bg-purple-100 rounded-xl flex items-center justify-center border border-purple-100/50 transition-colors">
                <x-icons.users width="22" height="22" class="text-purple-600" />
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-50 text-xs text-slate-400 truncate">
            <span>ID: {{ Str::limit(auth()->user()->id, 12, '...') }}</span>
        </div>
    </div>
</div>

<!-- SECTION 2: LIVE PROPORTION GRAPH AREA -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Proporsi Tipe Data Aktif -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5 pb-2 border-b border-slate-100 flex items-center gap-1.5">
            <span>📊 Proporsi Entitas yang Disetujui</span>
        </h3>
        
        <div class="space-y-5">
            <!-- Progress 1: Karakter -->
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-slate-600">👤 Karakter (Characters)</span>
                    <span class="text-slate-900 font-mono">{{ $characterCount }} Node</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-slate-950 h-full rounded-full transition-all duration-500" style="width: {{ $totalEntities > 0 ? ($characterCount / $totalEntities) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Progress 2: Senjata / Item -->
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-slate-600">⚔️ Pusaka & Objek (Items)</span>
                    <span class="text-slate-900 font-mono">{{ $itemCount }} Node</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-sky-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalEntities > 0 ? ($itemCount / $totalEntities) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Progress 3: Tempat / Sekte -->
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-slate-600">🏰 Lokasi & Faksi (Places)</span>
                    <span class="text-slate-900 font-mono">{{ $placeCount }} Node</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-cyan-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalEntities > 0 ? ($placeCount / $totalEntities) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Link Panel -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between gap-4">
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pb-2 border-b border-slate-100">
                🚀 Navigasi Cepat Panel
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Gunakan jalan pintas eksekusi di bawah ini untuk langsung masuk ke modul kurasi entitas kata kunci atau melakukan moderasi pengajuan baru.
            </p>
        </div>
        
        <div class="space-y-2">
            <a href="#" class="w-full inline-flex items-center justify-center bg-slate-950 text-white text-xs font-bold py-2.5 rounded-lg hover:bg-slate-800 active:scale-[0.99] transition-all shadow-xs cursor-pointer">
                ➕ Ajukan Judul Novel Baru
            </a>
            <a href="#" class="w-full inline-flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold py-2.5 rounded-lg hover:bg-slate-100 active:scale-[0.99] transition-all cursor-pointer">
                🔍 Tinjau Antrean Pending ({{ $pendingNovels + $pendingEntities }})
            </a>
        </div>
    </div>
</div>

@endsection