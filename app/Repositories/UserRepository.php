<?php

namespace App\Repositories;

use App\Enums\RolesPermissionEnum;
use App\Models\User;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
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
}
