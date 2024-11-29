<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends  BaseRepository<File>
 */
class FileRepository extends BaseRepository
{
    protected string $modelClass = File::class;

    /**
     * @param array $ids
     * @param array $relations
     * @return array<File>|Collection<File>
     */
    public function getByIds(array $ids, array $relations = []): array|Collection
    {
        return $this->globalQuery($relations)
            ->whereIn('id', $ids)
            ->get();
    }
}
