<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->entity->id,
            'main_name'   => $this->entity->main_name,
            'type'        => $this->entity->type, // 'character', 'item', atau 'place'
            'image_url'   => $this->entity->image_url,
            'description' => $this->entity->description,
            'matched_as'  => $this->alias_name, // Info nama apa yang memicu kecocokan

            'all_aliases' => $this->entity->aliases->pluck('alias_name')->toArray(),
        ];
    }
}
