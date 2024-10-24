<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\BaseRepository;

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

    public function getByFcmToken($fcm_token)
    {
        return User::where('fcm_token', $fcm_token)->get();
    }
}
