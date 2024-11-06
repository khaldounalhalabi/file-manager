<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Contracts\BaseRepository;

/**
 * @extends  BaseRepository<File>
 */
class FileRepository extends BaseRepository
{
    protected string $modelClass = File::class;
}
