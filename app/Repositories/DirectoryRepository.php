<?php

namespace App\Repositories;

use App\Models\Directory;
use App\Repositories\Contracts\BaseRepository;

/**
 * @extends  BaseRepository<Directory>
 */
class DirectoryRepository extends BaseRepository
{
    protected string $modelClass = Directory::class;
}
