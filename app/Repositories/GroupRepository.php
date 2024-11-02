<?php

namespace  App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\BaseRepository;

/**
 * @extends  BaseRepository<Group>
 */
class GroupRepository extends BaseRepository
{
    protected string $modelClass = Group::class;
}
