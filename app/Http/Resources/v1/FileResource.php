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
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'group_id' => $this->group_id,
            'directory_id' => $this->directory_id,
            'status' => $this->status,
            'group' => new GroupResource($this->whenLoaded('group')),
            'owner' => $this->whenLoaded('owner'),
            'directory' => $this->whenLoaded('directory'),
            'fileVersions' => FileVersionResource::collection($this->whenLoaded('fileVersions')),
            'last_version' => new FileVersionResource($this->whenLoaded('lastVersion')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
