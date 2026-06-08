<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Novel extends Model
{
    use HasUlids; // Memberitahu Laravel bahwa model ini otomatis pakai ULID

    protected $fillable = ['title', 'slug','status','user_id']; // Tambahkan 'status' dan 'user_id' ke fillable

    protected static function booted(): void
    {
        // Jika data novel diubah atau dihapus, bersihkan cache kata kuncinya
        static::saved(function ($novel) {
            static::clearNovelCache($novel->slug);
        });

        static::deleted(function ($novel) {
            static::clearNovelCache($novel->slug);
        });
    }

    protected static function clearNovelCache($slug): void
    {
        Cache::forget("novel_keywords_{$slug}_id");
        Cache::forget("novel_keywords_{$slug}_en");
        \Log::info("Cache Novel {$slug} dibersihkan karena ada perubahan pada master Novel.");
    }

    /**
     * Relasi: Satu novel bisa memiliki banyak entitas (karakter/item/tempat)
     */
    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
