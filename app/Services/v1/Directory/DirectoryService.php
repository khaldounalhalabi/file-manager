<?php

namespace App\Services\v1\Directory;

use App\Models\Directory;
use App\Repositories\DirectoryRepository;
use App\Repositories\GroupRepository;
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

    public function getRootByGroup($groupId, array $relations = []): ?array
    {
        return $this->repository->getRoot($groupId, $relations);
    }

    public function getRoot(array $relations = []): ?array
    {
        return $this->repository->getRoot($this->user?->group_id, $relations);
    }

    public function store(array $data, array $relationships = []): Model
    {
        if ($this->user->isAdmin()) {
            $data['owner_id'] = GroupRepository::make()->find($data['group_id'])?->owner_id;
        } else {
            $data['owner_id'] = $this->user->isAdmin() ? $data['group_id'] : $this->user?->id;
            $data['group_id'] = $this->user?->group_id;
        }

        return $this->repository->create($data, $relationships);
    }

    public function delete($id): ?bool
    {
        $directory = $this->repository->find($id);

        if (!$directory) {
            return false;
        }

        if (!$directory->canDelete()) {
            return false;
        }

        return $this->repository->delete($directory);
    }

    public function update(array $data, $id, array $relationships = []): ?Directory
    {
        $directory = $this->repository->find($id);

        if (!$directory) {
            return null;
        }

        if (!$directory->canUpdate()) {
            return null;
        }

        return $this->repository->update($data, $directory, $relationships);
    }
}
