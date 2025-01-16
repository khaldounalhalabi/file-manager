<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @property string type
 * @property int    notifiable_id
 * @property string notifiable_type
 * @property string data
 * @property Carbon read_at
 * @property bool   is_available
 */
class Notification extends DatabaseNotification
{
    protected $fillable = [
        'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
