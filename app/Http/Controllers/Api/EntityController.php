<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EntityResource;
use App\Services\EntityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    protected EntityService $entityService;

    // Inject EntityService ke dalam Controller (Dependency Injection)
    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    /**
     * Endpoint untuk pencarian entitas (Karakter/Item/Tempat)
     */
    public function search(Request $request): JsonResponse
    {
        $novelSlug = $request->query('novel_slug');
        $keyword = $request->query('keyword');

        // Validasi input wajib ada
        if (!$novelSlug || !$keyword) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Missing required parameters: novel_slug or keyword'
            ], 400);
        }

        // Panggil service untuk mencari data (termasuk auto-cache di dalamnya)
        $alias = $this->entityService->searchEntity($novelSlug, $keyword);

        if (!$alias) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'No data found for this keyword in this novel context.'
            ], 404);
        }

        // Kembalikan data yang sudah diformat rapi lewat EntityResource
        return response()->json([
            'status' => 'success',
            'data'   => new EntityResource($alias)
        ]);
    }

    /**
     * Endpoint untuk mengambil SEMUA alias dari satu novel tertentu (Untuk Auto-Scanner)
     */
    public function getAllAliases(Request $request): JsonResponse
    {
        $novelSlug = $request->query('novel_slug');
        $locale = $request->query('locale', 'en'); // Default ke 'en' jika ekstensi tidak mengirim data locale

        if (!$novelSlug) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameter'], 400);
        }

        // Kita masukkan locale ke dalam nama cache agar cache antar bahasa tidak tertukar
        $cacheKey = "novel:aliases:{$novelSlug}:{$locale}";
        
        $aliases = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addDay(), function () use ($novelSlug, $locale) {
            return \App\Models\EntityAlias::whereHas('entity.novel', function ($query) use ($novelSlug) {
                $query->where('slug', $novelSlug);
            })
            ->whereHas('entity', function ($query) {
                // KUNCI: Ekstensi hanya mengambil data yang sudah disetujui oleh Admin Utama!
                $query->where('status', 'approved'); 
            })
            ->with(['entity.translations'])
            ->get()
            ->map(function ($alias) use ($locale) {
                $entity = $alias->entity;
                
                // Ambil deskripsi berdasarkan bahasa pilihan user
                $description = $entity->getDescriptionByLocale($locale);

                // Melokalisasi Teks Tipe (Type) agar menyesuaikan bahasa
                $translatedType = $entity->type;
                if ($locale === 'id') {
                    if ($entity->type === 'character') $translatedType = 'Karakter';
                    if ($entity->type === 'item') $translatedType = 'Benda/Pusaka';
                    if ($entity->type === 'place') $translatedType = 'Tempat/Lokasi';
                }

                return [
                    'keyword'         => $alias->alias_name,
                    'main_name'       => $entity->main_name,
                    'type'            => $translatedType, // Sudah diterjemahkan!
                    'gender'          => $entity->gender, // Menyertakan data gender
                    'image_url'       => $entity->image_url,
                    'description'     => $description, // Sudah diterjemahkan!
                    'display_aliases' => $entity->display_aliases, 
                ];
            });
        });

        return response()->json(['status' => 'success', 'data' => $aliases]);
    }
}
