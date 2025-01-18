<?php

namespace App\Services\v1\FileLog;

use App\Models\FileLog;
use App\Repositories\FileLogRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;

/**
 * @extends BaseService<FileLog>
 * @property FileLogRepository $repository
 */
class FileLogService extends BaseService
{
    use Makable;

    protected string $repositoryClass = FileLogRepository::class;

    public function getByFile($fileId, array $relations = []): ?array
    {
        return $this->repository->getByFile($fileId, $relations);
    }

    public function getByUser(int $userId, array $relations = []): ?array
    {
        return $this->repository->getByUser($userId, $relations);
    }
}
