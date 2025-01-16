<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\BaseResource;
use App\Models\Notification;
use Illuminate\Http\Request;

/** @mixin Notification */
class NotificationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'type' => str_replace("App\\Notifications\\", "", $this->type),
            'message' => $this->getMessage(),
            'read_at' => $this->read_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function getMessage()
    {
        $lang = app()->getLocale();
        $notificationData = json_decode($this->data, true);
        return $notificationData["message_$lang"] ?? "";
    }
}
