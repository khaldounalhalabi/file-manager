<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\BaseResource;

/** @mixin \App\Models\FileVersion */
class FileVersionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_path' => $this->file_path,
            'file_id' => $this->file_id,
            'version' => $this->version,
            'file' => new FileResource($this->whenLoaded('file')),
        ];
    }
}
