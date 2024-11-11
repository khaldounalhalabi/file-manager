<?php

namespace App\Repositories;

use App\Enums\RolesPermissionEnum;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionAlias;
use LaravelIdea\Helper\App\Models\_IH_User_C;

/**
 * @extends  BaseRepository<User>
 */
class UserRepository extends BaseRepository
{
    protected string $modelClass = User::class;

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getUserByPasswordResetCode($token)
    {
        return User::where('reset_password_code', $token)->first();
    }

    public function getByFcmToken($fcm_token): Collection|array|_IH_User_C
    {
        return User::where('fcm_token', $fcm_token)->get();
    }

    public function getByGroup($groupId, array $relations = []): ?array
    {
        return $this->paginate(
            $this->globalQuery($relations)
                ->role(RolesPermissionEnum::CUSTOMER['role'])
                ->whereHas('groups', function ($query) use ($groupId) {
                    $query->where('groups.id', $groupId);
                })
        );
    }

    public function getCustomers(array $relations = []): ?array
    {
        return $this->paginate(
            $this->globalQuery($relations)
                ->role(RolesPermissionEnum::CUSTOMER['role'])
        );
    }

    /**
     * @param Group $group
     * @return void
     */
    public function removeUsersFromGroup(Group $group): void
    {
        $group->users()->chunk(10,
            /**
             * @param Collection<User>|CollectionAlias<User> $users
             */
            function (Collection|CollectionAlias $users) use ($group) {
                foreach ($users as $user) {
                    if ($user->group_id === $group->id) {
                        $this->update([
                            'group_id' => null
                        ], $user);
                    }

                    $user->groups()->detach($group->id);
                }
            });
    }
}
