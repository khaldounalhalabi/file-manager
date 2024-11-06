<?php

namespace App\Services\v1\File;

use App\Models\File;
use App\Repositories\FileRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;

/**
 * @extends BaseService<File>
 * @property FileRepository $repository
 */
class FileService extends BaseService
{
    use Makable;

    protected string $repositoryClass = FileRepository::class;
}
