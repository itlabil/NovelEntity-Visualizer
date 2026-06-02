<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class EntityTranslation extends Model
{
    use HasUlids; // Mengaktifkan fitur ULID otomatis

    protected $fillable = ['entity_id', 'locale', 'description'];
    
}
