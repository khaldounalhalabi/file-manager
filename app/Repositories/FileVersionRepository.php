<?php

namespace App\Repositories;

use App\Models\FileVersion;
use App\Repositories\Contracts\BaseRepository;

/**
 * @extends  BaseRepository<FileVersion>
 */
class FileVersionRepository extends BaseRepository
{
    protected string $modelClass = FileVersion::class;
}
