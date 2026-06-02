<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    use HasUlids; // Memberitahu Laravel bahwa model ini otomatis pakai ULID

    protected $fillable = ['title', 'slug'];

    /**
     * Relasi: Satu novel bisa memiliki banyak entitas (karakter/item/tempat)
     */
    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }
}
