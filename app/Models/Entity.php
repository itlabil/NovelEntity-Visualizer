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

    public $afterCommit = true;
    
    protected $fillable = [
        'novel_id',
        'main_name',
        'type',
        'gender',
        'image_url',
        'display_aliases',
        'status',
        'user_id'
    ];

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
