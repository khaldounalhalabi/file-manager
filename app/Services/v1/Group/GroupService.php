<?php

namespace App\Services\v1\Group;

use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as CollectionAlias;

/**
 * @extends BaseService<Group>
 * @property GroupRepository $repository
 */
class GroupService extends BaseService
{
    use Makable;

    protected string $repositoryClass = GroupRepository::class;

    public function store(array $data, array $relationships = []): Model
    {
        /** @var Group $group */
        $group = parent::store($data, $relationships);

        if (isset($data['users'])) {
            $group->users()->sync($data['users']);
        }

        if ($this->user?->isCustomer()) {
            $this->user->update([
                'group_id' => $group->id
            ]);
        }

        return $group;
    }

    public function update(array $data, $id, array $relationships = []): ?Model
    {
        /** @var Group|null $group */
        $group = parent::update($data, $id, $relationships);

        if (isset($data['users'])) {
            $group?->users()?->sync($data['users']);
        }

        return $group;
    }

    /**
     * @param array $relations
     * @return Collection|array|CollectionAlias
     */
    public function getUserGroups(array $relations = []): Collection|array|CollectionAlias
    {
        return $this->repository->getByUser(auth()->user()->id, $relations);
    }

    public function selectGroup($groupId): void
    {
        $this->user->update([
            'group_id' => $groupId
        ]);
    }

    public function delete($id): ?bool
    {
        $group = $this->repository->find($id);

        if (!$group) {
            return null;
        }

        $group->owner->update([
            'group_id' => null
        ]);

        return $this->repository->delete($group);
    }

    public function changeUserGroup($groupId)
    {
        $group = $this->repository->find($groupId);
        if (!$group) {
            return null;
        }

        if ($group->owner_id != $this->user?->id && !$group->users()->where('users.id', $this->user?->id)->exists()) {
            return null;
        }

        UserRepository::make()->update([
            'group_id' => $group->id,
        ], $this->user);

        return $group;
    }
}
