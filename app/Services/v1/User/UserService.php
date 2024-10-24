<?php

namespace App\Services\v1\User;

use App\Enums\RolesPermissionEnum;
use App\Models\User;
use App\Notifications\ResetPasswordCodeEmail;
use App\Repositories\UserRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
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
     * @return array{User , string , string}|User|null
     */
    public function updateUserDetails(array $data, ?string $role = null): array|User|null
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

            return [$user, $token, $refresh_token,];
        }

        return $user;
    }

    /**
     * @param array       $data
     * @param string|null $role
     * @param array       $additionalData
     * @return User|Authenticatable|array{User , string , string}|null
     */
    public function login(array $data, ?string $role = null, array $additionalData = []): User|Authenticatable|array|null
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

            return [$user, $token, $refresh_token,];
        }

        return $user;
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
     * @return array{User , string , string}|null
     */
    public function refresh_token(): ?array
    {
        try {
            $user = auth($this->guard)->user();
            $token = auth($this->guard)->setTTL(env('JWT_TTL', 10080))->refresh();
            $refresh_token = auth($this->guard)->setTTL(env('JWT_REFRESH_TTL', 20160))->refresh();

            return [$user, $token, $refresh_token];
        } catch (Exception) {
            return null;
        }
    }

    /**
     * @param array       $data
     * @param string|null $role
     * @return array{User , string , string}|User
     */
    public function register(array $data, ?string $role = null): array|User
    {
        $user = $this->repository->create($data);

        if ($role) {
            $user->assignRole($role);
        }

        $token = auth($this->guard)->login($user);

        if (!request()->acceptsHtml()) {
            $refresh_token = auth($this->guard)->setTTL(ttl: env('JWT_REFRESH_TTL', 20160))->refresh();

            return [$user, $token, $refresh_token,];
        }

        return $user;
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
     * @param $email
     * @return User|null
     */
    public function getUserByEmail($email): ?User
    {
        return $this->repository->getUserByEmail($email);
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
     * @return User|Authenticatable|null
     */
    public function userDetails(?string $role = null): User|Authenticatable|null
    {
        $user = auth($this->guard)->user();

        if (!$user) {
            return null;
        }

        if ($role && !$user->hasRole($role)) {
            return null;
        }

        return $user;
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
                //TODO::create user group if a customer
            }

            DB::commit();
            return $user;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
