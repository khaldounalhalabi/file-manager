<?php

namespace App\Services\v1\Directory;

use App\Models\Directory;
use App\Repositories\DirectoryRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;

/**
 * @extends BaseService<Directory>
 * @property DirectoryRepository $repository
 */
class DirectoryService extends BaseService
{
    use Makable;

    protected string $repositoryClass = DirectoryRepository::class;
}
