<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\BaseResource;
use App\Models\File;

/** @mixin File */
class FileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'group_id' => $this->group_id,
            'directory_id' => $this->directory_id,
            'status' => $this->status,
            'group' => new GroupResource($this->whenLoaded('group')),
            'owner' => $this->whenLoaded('owner'),
            'directory' => $this->whenLoaded('directory')
        ];
    }
}
