<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\BaseResource;

/**
 * @mixin \App\Models\Group
 */
class GroupResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
        ];
    }
}
