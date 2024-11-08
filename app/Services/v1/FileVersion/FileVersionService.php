<?php

namespace App\Services\v1\FileVersion;

use App\Models\FileVersion;
use App\Repositories\FileVersionRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;

/**
 * @extends BaseService<FileVersion>
 * @property FileVersionRepository $repository
 */
class FileVersionService extends BaseService
{
    use Makable;

    protected string $repositoryClass = FileVersionRepository::class;
}
