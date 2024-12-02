<?php

namespace App\Services\v1\User;

use App\Enums\RolesPermissionEnum;
use App\Models\User;
use App\Notifications\ResetPasswordCodeEmail;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/**
 * @extends BaseService<User>
 * @property UserRepository $repository
 */
class UserService extends BaseService
{
    use Makable;

    private string $guard = 'web';

    protected string $repositoryClass = UserRepository::class;

    /**
     * @param string $guard
     * @return void
     * @throws Exception
     */
    public function setGuard(string $guard = 'api'): void
    {
        if (!in_array($guard, array_keys(config('auth.guards')))) {
            throw new Exception("Undefined Guard : [$guard]");
        }

        $this->guard = $guard;
    }

    /**
     * @param array       $data
     * @param string|null $role
     * @param array       $relations
     * @return array{User , string , string}|User|null
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection LaravelFunctionsInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function updateUserDetails(array $data, ?string $role = null, array $relations = []): array|User|null
    {
        $user = auth($this->guard)->user();

        if (!$user) {
            return null;
        }

        if ($role && !$user->hasRole($role)) {
            return null;
        }

        /** @var User $user */
        $user = $this->repository->update($data, $user->id);

        $token = auth($this->guard)->login($user);

        if (!request()->acceptsHtml()) {
            $refresh_token = auth($this->guard)->setTTL(ttl: env('JWT_REFRESH_TTL', 20160))->refresh();

            return [$user->load($relations), $token, $refresh_token,];
        }

        return $user->load($relations);
    }

    /**
     * @param array       $data
     * @param string|null $role
     * @param array       $additionalData
     * @param array       $relations
     * @return User|Authenticatable|array{User , string , string}|null
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection LaravelFunctionsInspection
     */
    public function login(array $data, ?string $role = null, array $additionalData = [], array $relations = []): User|Authenticatable|array|null
    {
        $token = auth($this->guard)->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (!$token) {
            return null;
        }

        $user = auth($this->guard)->user();

        if ($role && !$user->hasRole($role)) {
            return null;
        }

        if (isset($data['fcm_token']) && $data['fcm_token']) {
            $this->clearFcmTokenFromOtherUsers($data['fcm_token']);
            $user->fcm_token = $data['fcm_token'];
            $user->save();
        }

        foreach ($additionalData as $key => $value) {
            $user->{$key} = $value;
            $user->save();
        }

        if (!request()->acceptsHtml()) {
            $refresh_token = auth($this->guard)->setTTL(ttl: env('JWT_REFRESH_TTL', 20160))->refresh();

            return [$user->load($relations), $token, $refresh_token,];
        }

        return $user->load($relations);
    }

    /**
     * @param $fcm_token
     * @return void
     */
    public function clearFcmTokenFromOtherUsers($fcm_token): void
    {
        $users = $this->repository->getByFcmToken($fcm_token);
        foreach ($users as $user) {
            $user->fcm_token = null;
            $user->save();
        }
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $user = auth($this->guard)->user();
        auth($this->guard)->logout();
        $user->fcm_token = null;
        $user->save();
    }

    /**
     * @param array       $data
     * @param string|null $role
     * @param array       $relations
     * @return array{User , string , string}|User
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection LaravelFunctionsInspection
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    public function register(array $data, ?string $role = null, array $relations = []): array|User
    {
        $user = $this->repository->create($data);

        if ($role) {
            $user->assignRole($role);
        }

        $token = auth($this->guard)->login($user);

        if (!request()->acceptsHtml()) {
            $refresh_token = auth($this->guard)->setTTL(ttl: env('JWT_REFRESH_TTL', 20160))->refresh();

            return [$user->load($relations), $token, $refresh_token,];
        }

        return $user->load($relations);
    }

    /**
     * @param string $email
     * @return bool|null
     */
    public function passwordResetRequest(string $email): ?bool
    {
        $user = $this->getUserByEmail($email);

        if ($user) {
            do {
                $code = sprintf('%06d', mt_rand(1, 999999));
                $temp_user = $this->getUserByPasswordResetCode($code);
            } while ($temp_user != null);

            $user->reset_password_code = $code;
            $user->save();

            try {
                $user->notify(new ResetPasswordCodeEmail($code));
            } catch (Exception) {
                return null;
            }

            return true;
        }

        return null;
    }

    /**
     * @param       $email
     * @param array $relations
     * @return User|null
     */
    public function getUserByEmail($email, array $relations = []): ?User
    {
        return $this->repository->getUserByEmail($email)?->load($relations);
    }

    /**
     * @param $token
     * @return User|null
     */
    public function getUserByPasswordResetCode($token): ?User
    {
        return $this->repository->getUserByPasswordResetCode($token);
    }

    /**
     * @param string $reset_password_code
     * @param string $password
     * @return bool
     */
    public function passwordReset(string $reset_password_code, string $password): bool
    {
        $user = $this->getUserByPasswordResetCode($reset_password_code);

        if ($user) {
            $user->password = $password;
            $user->reset_password_code = null;
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * @param string|null $role
     * @param array       $relations
     * @return User|Authenticatable|null
     */
    public function userDetails(?string $role = null, array $relations = []): User|Authenticatable|null
    {
        $user = auth($this->guard)->user();

        if (!$user) {
            return null;
        }

        if ($role && !$user->hasRole($role)) {
            return null;
        }

        return $user->load($relations);
    }

    /**
     * @throws Exception
     */
    public function store(array $data, array $relationships = []): Model
    {
        try {
            DB::beginTransaction();
            $user = $this->repository->create($data);

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            } else {
                throw new Exception("The created user don't have a role");
            }

            if ($data['role'] == RolesPermissionEnum::CUSTOMER['role']) {
                $group = GroupRepository::make()->create([
                    'name' => $data['group_name'],
                    'owner_id' => $user->id,
                ]);

                $user->update([
                    'group_id' => $group->id
                ]);
            }

            DB::commit();
            return $user;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getByGroup($groupId, array $relations = []): ?array
    {
        return $this->repository->getByGroup($groupId, $relations);
    }

    public function getCustomers(array $relations = []): ?array
    {
        return $this->repository->getCustomers($relations);
    }


    public function acceptInvitation(string $token): ?bool
    {
        try {
            $tokenData = Crypt::decrypt($token);
            $validUntil = Carbon::parse($tokenData['valid_until']);

            if ($validUntil->isBefore(now())) {
                return false;
            }

            $user = $this->repository->getUserByEmail($tokenData['email']);
            if (!$user) {
                return false;
            }

            $group = GroupRepository::make()->find($tokenData['group_id']);
            if (!$group) {
                return false;
            }

            $user = $this->repository->update([
                'group_id' => $group->id,
            ], $user);

            if (!$user->groups()->where('groups.id', $group->id)->exists()) {
                $user->groups()->attach($group->id);
            }

            auth('web')->login($user);

            return true;
        } catch (\Exception) {
            return null;
        }
    }
}
