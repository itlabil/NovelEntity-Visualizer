@push('styles')
    <style>
        /* Mengontrol Warna Input & Select */
        html body .dt-container .dt-search input,
        html body .dt-container .dt-length select {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            outline: none !important;
        }
        
        html body .dt-container .dt-search input:focus,
        html body .dt-container .dt-length select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
        }

        /* 1. WARNA DASAR TOMBOL PAGINASI (Mematikan efek Dark Mode) */
        html body ul.pagination a {
            background-color: #ffffff !important; /* Paksa latar putih */
            color: #374151 !important; /* Paksa teks abu-abu gelap */
            border-color: #d1d5db !important; /* Paksa border abu-abu terang */
        }

        /* 2. EFEK HOVER SAAT DISOROT MOUSE */
        html body ul.pagination a:hover:not([aria-disabled="true"]) {
            background-color: #f3f4f6 !important; /* bg-gray-100 */
            color: #111827 !important; /* Teks hitam */
        }

        /* 3. 🔥 HALAMAN YANG SEDANG AKTIF (Targeting atribut aria-current="page") 🔥 */
        html body ul.pagination a[aria-current="page"] {
            background-color: #3b82f6 !important; /* Warna Biru Tailwind (bg-blue-500) */
            color: #ffffff !important; /* Teks putih */
            border-color: #3b82f6 !important; /* Border biru */
        }

        /* 4. TOMBOL PREV/NEXT YANG DISABLE (Mentok) */
        html body ul.pagination a[aria-disabled="true"] {
            background-color: #f9fafb !important; /* bg-gray-50 */
            color: #9ca3af !important; /* text-gray-400 */
            border-color: #e5e7eb !important;
            cursor: not-allowed !important;
        }
    </style>
@endpush
