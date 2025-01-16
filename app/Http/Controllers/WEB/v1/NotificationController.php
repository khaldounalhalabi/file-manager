<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\NotificationResource;
use App\Services\v1\Notification\NotificationService;
use App\Traits\RestTrait;

class NotificationController extends Controller
{
    use RestTrait;

    private NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = NotificationService::make();
    }

    public function getUserNotification()
    {
        $notifications = $this->notificationService->getUserNotifications();
        if ($notifications) {
            return $this->apiResponse(NotificationResource::collection($notifications['data']), ApiController::STATUS_OK, __('site.get_successfully'), $notifications['pagination_data']);
        }
        return $this->noData();
    }

    public function markAsRead($notificationId)
    {
        $item = $this->notificationService->update([
            'read_at' => now(),
        ], $notificationId);

        if ($item) {
            return $this->apiResponse(true, ApiController::STATUS_OK, __('site.success'));
        }
        return $this->noData(false);
    }

    public function unreadCount()
    {
        return $this->apiResponse(
            auth()->user()?->unreadNotifications()->count(),
            ApiController::STATUS_OK,
            __('site.success')
        );
    }

    public function markAllAsRead()
    {
        auth()?->user()?->notifications()->update(['read_at' => now()]);
        return $this->apiResponse(true, ApiController::STATUS_OK, __('site.success'));
    }
}
