<?php

namespace App\Http\Resources;

use App\Http\Resources\v1\GroupResource;
use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Directory */
class DirectoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'path' => $this->path,
            'id' => $this->id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            'owner_id' => $this->owner_id,
            'parent_id' => $this->parent_id,
            'group_id' => $this->group_id,

            'parent' => new DirectoryResource($this->whenLoaded('parent')),
            'group' => new GroupResource($this->whenLoaded('group')),
            'subDirectories' => DirectoryResource::collection($this->whenLoaded('subDirectories')),
        ];
    }
}
