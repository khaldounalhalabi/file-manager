<?php

namespace App\Services\v1\Directory;

use App\Models\Directory;
use App\Repositories\DirectoryRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseService<Directory>
 * @property DirectoryRepository $repository
 */
class DirectoryService extends BaseService
{
    use Makable;

    protected string $repositoryClass = DirectoryRepository::class;

    public function getRoot(array $relations = []): ?array
    {
        return $this->repository->getRoot($this->user?->group_id, $relations);
    }

    public function store(array $data, array $relationships = []): Model
    {
        $data['group_id'] = $this->user?->group_id;
        $data['owner_id'] = $this->user?->id;
        return $this->repository->create($data, $relationships);
    }
}
