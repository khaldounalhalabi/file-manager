<?php

namespace App\Http\Resources\v1;

use App\Enums\FileLogTypeEnum;
use App\Http\Resources\BaseResource;
use App\Models\FileLog;

/** @mixin FileLog */
class FileLogResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_id' => $this->file_id,
            'event_type' => $this->getMessage($this->event_type),
            'user_id' => $this->user_id,
            'happened_at' => $this->happened_at,
            'file' => new FileResource($this->whenLoaded('file')),
            'user' => $this->whenLoaded('user'),
        ];
    }

    private function getMessage(string $eventType): string
    {
        $event = FileLogTypeEnum::tryFrom($eventType);
        return match ($event) {
            FileLogTypeEnum::CREATED => $this->user->first_name . ' ' . $this->user->last_name . ' created the file at ' . $this->happened_at->format('Y-m-d H:i:s'),
            FileLogTypeEnum::STARTED_EDITING => $this->user->first_name . ' ' . $this->user->last_name . ' started editing the file at ' . $this->happened_at->format('Y-m-d H:i:s'),
            FileLogTypeEnum::FINISHED_EDITING => $this->user->first_name . ' ' . $this->user->last_name . ' pushed new updates to the file at ' . $this->happened_at->format('Y-m-d H:i:s'),
            FileLogTypeEnum::DELETED => $this->user->first_name . ' ' . $this->user->last_name . ' has deleted the file at ' . $this->happened_at->format('Y-m-d H:i:s'),
            default => "No corresponding message to the current event"
        };
    }
}
