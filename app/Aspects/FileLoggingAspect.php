<?php

namespace App\Aspects;

use App\Aspects\Contracts\Aspect;
use App\Enums\FileLogTypeEnum;
use App\Repositories\FileLogRepository;

class FileLoggingAspect extends Aspect
{
    public function after(string $method, array $args, $result): void
    {
        switch ($method) {
            case "store" :
                FileLogRepository::make()->logEvent(FileLogTypeEnum::CREATED, $result);
                break;
            case "edit":
                if ($result) {
                    FileLogRepository::make()->logEvent(FileLogTypeEnum::STARTED_EDITING, $args[0]);
                }
                break;
            case "pushUpdates":
                if ($result) {
                    FileLogRepository::make()->logEvent(FileLogTypeEnum::FINISHED_EDITING, $args[0]['file_id']);
                }
                break;
            case "delete":
                if ($result) {
                    FileLogRepository::make()->logEvent(FileLogTypeEnum::DELETED, $args[0]);
                }
                break;
            case "zipMultipleFiles":
                if ($result) {
                    foreach ($args[0]['files_ids'] as $fileId) {
                        FileLogRepository::make()->logEvent(FileLogTypeEnum::STARTED_EDITING, $fileId);
                    }
                }
                break;
            default :
                break;
        }
    }
}
