<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionAlias;

/**
 * @extends  BaseRepository<Group>
 */
class GroupRepository extends BaseRepository
{
    protected string $modelClass = Group::class;

    public function globalQuery(array $relations = [], $defaultOrder = true): Builder|Group
    {
        return parent::globalQuery($relations)
            ->when(auth()->user()?->isCustomer(),
                fn(Builder|Group $query) => $query
                    ->where('owner_id', auth()->user()?->id)
                    ->orWhereHas('users', function ($query) {
                        $query->where('users.id', auth()->user()?->id);
                    }));
    }

    /**
     * @param       $userId
     * @param array $relations
     * @return Collection<Group>|CollectionAlias<Group>|array<Group>
     */
    public function getByUser($userId, array $relations = []): Collection|CollectionAlias|array
    {
        return $this->globalQuery($relations)
            ->where('owner_id', $userId)
            ->orWhereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })->get();
    }
}
