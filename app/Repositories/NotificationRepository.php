<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\User;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends  BaseRepository<Notification>
 */
class NotificationRepository extends BaseRepository
{
    protected string $modelClass = Notification::class;

    public function globalQuery(array $relations = [], $defaultOrder = true): Builder|Notification
    {
        return parent::globalQuery($relations)
            ->where('type', 'NOT LIKE', '%RealTime%');
    }

    public function getUserNotifications($notifiableId, $notifiableType = User::class, int $perPage = 10): ?array
    {
        return $this->paginate(
            $this->notificationsBaseQuery($notifiableId, $notifiableType),
            $perPage
        );
    }

    private function notificationsBaseQuery($notifiableId, $notifiableType = User::class, bool $isAvailable = true)
    {
        return $this->globalQuery()
            ->when($isAvailable, fn($q) => $q->available())
            ->where('notifiable_id', $notifiableId)
            ->where('notifiable_type', $notifiableType);
    }

    public function getUnreadNotificationCounter($notifiableId, $notifiableType = User::class): int
    {
        return $this->notificationsBaseQuery($notifiableId, $notifiableType)
            ->where('read_at', null)
            ->count();
    }

    public function markAllNotificationsAsRead($notifiableId, $notifiableType = User::class): int
    {
        return $this->notificationsBaseQuery($notifiableId, $notifiableType)
            ->whereNull('read_at')
            ->update(['read_at' => now()->format('Y-m-d H:i:s')]);
    }
}
