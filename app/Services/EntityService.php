<?php

namespace App\Services;

use App\Models\EntityAlias;
use Illuminate\Support\Facades\Cache;

class EntityService
{
    /**
     * Mencari entitas berdasarkan slug novel dan kata kunci, menggunakan sistem Cache.
     */
    public function searchEntity(string $novelSlug, string $keyword)
    {
        // 1. Buat key unik untuk cache (Format: entity:slug-novel:keyword)
        $cacheKey = "entity:{$novelSlug}:" . md5(strtolower($keyword));

        // 2. Ambil dari cache, jika tidak ada, jalankan query database dan simpan ke cache selama 24 jam
        return Cache::remember($cacheKey, now()->addDay(), function () use ($novelSlug, $keyword) {
            return EntityAlias::where('alias_name', $keyword)
                ->whereHas('entity.novel', function ($query) use ($novelSlug) {
                    $query->where('slug', $novelSlug);
                })
                ->with('entity')
                ->first();
        });
    }
}