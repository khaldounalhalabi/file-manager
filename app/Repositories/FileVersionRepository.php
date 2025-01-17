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

    public function getByFile($fileId, array $relations = []): ?array
    {
        return $this->paginate(
            $this->globalQuery($relations)
                ->where('file_id', $fileId)
                ->when(!auth()->user()->isAdmin(), function ($query) {
                    $query->whereHas('file', fn($q) => $q->where('group_id', auth()->user()->group_id));
                })
        );
    }
}
