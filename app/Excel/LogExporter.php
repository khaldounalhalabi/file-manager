<?php

namespace App\Excel;

use App\Enums\FileLogTypeEnum;
use App\Models\FileLog;
use Illuminate\Database\Eloquent\Model;

class LogExporter extends BaseExporter
{
    protected function cast(mixed $value, string $attribute, FileLog|Model $model): mixed
    {
        if ($attribute == 'event_type') {
            $event = FileLogTypeEnum::tryFrom($value);
            return match ($event) {
                FileLogTypeEnum::CREATED => $model->user->first_name . ' ' . $model->user->last_name . ' created the file at ' . $model->happened_at->format('Y-m-d H:i:s'),
                FileLogTypeEnum::STARTED_EDITING => $model->user->first_name . ' ' . $model->user->last_name . ' started editing the file at ' . $model->happened_at->format('Y-m-d H:i:s'),
                FileLogTypeEnum::FINISHED_EDITING => $model->user->first_name . ' ' . $model->user->last_name . ' pushed new updates to the file at ' . $model->happened_at->format('Y-m-d H:i:s'),
                default => "No corresponding message to the current event"
            };
        } elseif ($attribute == "happened_at") {
            return $value->format('Y-m-d H:i:s');
        } else {
            return $value;
        }
    }
}
