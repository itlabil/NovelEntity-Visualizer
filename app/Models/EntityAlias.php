<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityAlias extends Model
{
    use HasUlids; // Mengaktifkan fitur ULID otomatis

    protected $fillable = ['entity_id', 'alias_name'];

    /**
     * Relasi: Setiap nama alias ini merujuk pada satu entitas utama
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
