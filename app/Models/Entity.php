<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Entity extends Model
{
    use HasUlids; // Mengaktifkan fitur ULID otomatis

    protected $fillable = ['novel_id', 'main_name', 'type', 'image_url', 'status', 'user_id'];

    protected static function booted(): void
    {
        // 2. TRIGGER SAAT DATA DISIMPAN / DIUPDATE (Termasuk saat ganti status approved/rejected)
        static::saved(function ($entity) {
            static::clearNovelCache($entity);
        });

        // 3. TRIGGER SAAT DATA DIHAPUS
        static::deleted(function ($entity) {
            static::clearNovelCache($entity);
        });
    }

    protected static function clearNovelCache($entity): void
    {
        // Pastikan relasi ke novel ada untuk mengambil slug-nya
        if ($entity->novel) {
            $novelSlug = $entity->novel->slug;

            // Hapus cache untuk semua locale bahasa (id dan en)
            Cache::forget("novel_keywords_{$novelSlug}_id");
            Cache::forget("novel_keywords_{$novelSlug}_en");
            
            \Log::info("Cache untuk novel {$novelSlug} berhasil dibersihkan otomatis karena ada perubahan data.");
        }
    }

    /**
     * Relasi: Setiap entitas ini dimiliki oleh satu novel tertentu
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    /**
     * Relasi: Satu entitas bisa memiliki banyak nama alias/julukan
     */
    public function aliases(): HasMany
    {
        return $this->hasMany(EntityAlias::class);
    }

    public function translations()
    {
        return $this->hasMany(EntityTranslation::class);
    }

    // Helper untuk mengambil deskripsi sesuai locale atau fallback ke English jika tidak ada
    public function getDescriptionByLocale($locale = 'en')
    {
        $translation = $this->translations()->where('locale', $locale)->first();
        
        // Jika bahasa yang diminta tidak ada, pakai fallback bahasa Inggris ('en')
        if (!$translation) {
            $translation = $this->translations()->where('locale', 'en')->first();
        }
        
        return $translation ? $translation->description : $this->description;
    }
}
