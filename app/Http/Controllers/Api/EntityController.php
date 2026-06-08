<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EntityResource;
use App\Services\EntityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\EntityAlias;

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
    * Endpoint untuk mengambil SEMUA alias dari satu novel tertentu (Untuk Auto-Scanner)
    */
    public function getAllAliases(Request $request): JsonResponse
    {
        $novelSlug = $request->query('novel_slug');
        $locale = $request->query('locale', 'en'); 

        if (!$novelSlug) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameter'], 400);
        }

        // 🌟 KUNCI: Samakan format key cache dengan yang akan dihapus oleh Model Observer/Hooks!
        $cacheKey = "novel_keywords_{$novelSlug}_{$locale}";
        
        $aliases = Cache::remember($cacheKey, now()->addDay(), function () use ($novelSlug, $locale) {
            return EntityAlias::whereHas('entity.novel', function ($query) use ($novelSlug) {
                // 🔒 PROTEKSI 1: Novel WAJIB berstatus approved!
                $query->where('slug', $novelSlug)->where('status', 'approved');
            })
            ->whereHas('entity', function ($query) {
                // 🔒 PROTEKSI 2: Entity juga WAJIB berstatus approved!
                $query->where('status', 'approved'); 
            })
            ->with(['entity.translations'])
            ->get()
            ->map(function ($alias) use ($locale) {
                $entity = $alias->entity;
                
                $description = $entity->getDescriptionByLocale($locale);

                $translatedType = $entity->type;
                if ($locale === 'id') {
                    if ($entity->type === 'character') $translatedType = 'Karakter';
                    if ($entity->type === 'item') $translatedType = 'Benda/Pusaka';
                    if ($entity->type === 'place') $translatedType = 'Tempat/Lokasi';
                }

                return [
                    'keyword'         => $alias->alias_name,
                    'main_name'       => $entity->main_name,
                    'type'            => $translatedType, 
                    'gender'          => $entity->gender, 
                    'image_url'       => $entity->image_url,
                    'description'     => $description, 
                    'display_aliases' => $entity->display_aliases, 
                ];
            });
        });

        return response()->json(['status' => 'success', 'data' => $aliases]);
    }
}
