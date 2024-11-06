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

    public function getRoot($groupId, array $relations = []): ?array
    {
        return $this->paginate(
            $this->globalQuery($relations)
                ->where('group_id', $groupId)
                ->whereNull('parent_id')
        );
    }

    public function getByName(int $groupId, string $name, array $relations = []): ?Directory
    {
        return $this->globalQuery($relations)
            ->where('group_id', $groupId)
            ->where('name', $name)
            ->first();
    }
}
