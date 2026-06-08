<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class EntityTranslation extends Model
{
    use HasUlids; // Mengaktifkan fitur ULID otomatis

    protected $touches = ['entity'];
    protected $fillable = ['entity_id', 'locale', 'description'];

    // Relasi balik ke induk Entity
    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }
}
