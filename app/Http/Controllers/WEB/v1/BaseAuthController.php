<?php

namespace App\Http\Controllers\WEB\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AuthRequests\AuthLoginRequest;
use App\Http\Requests\v1\AuthRequests\AuthRegisterRequest;
use App\Http\Requests\v1\AuthRequests\CheckPasswordResetRequest;
use App\Http\Requests\v1\AuthRequests\RequestResetPasswordRequest;
use App\Http\Requests\v1\AuthRequests\ResetPasswordRequest;
use App\Http\Requests\v1\AuthRequests\UpdateUserRequest;
use App\Services\v1\User\UserService;
use Exception;
use Inertia\Inertia;

class BaseAuthController extends Controller
{
    protected UserService $userService;
    protected ?string $role = null;
    protected array $relations = [];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->userService = UserService::make();
        $this->userService->setGuard("web");
    }

    public function roleHook(string $role)
    {
        $this->role = $role;
    }

    public function login(AuthLoginRequest $request)
    {
        //you can pass additional data as an array for the third parameter in the
        //login method and this data will be stored in the users table
        $user = $this->userService->login($request->validated(), $this->role, $this->relations);
        if ($user) {
            return redirect()->route("v1.web.$this->role.user.details");
        } else {
            return redirect()->back();
        }
    }


    public function updateUserData(UpdateUserRequest $request)
    {
        $user = $this->userService->updateUserDetails($request->validated(), $this->role, $this->relations);

        if ($user) {
            return redirect()->route("v1.web.$this->role.user.details");
        } else {
            return redirect()->back();
        }
    }

    public function userDetails()
    {
        $user = $this->userService->userDetails($this->role, $this->relations);

        if ($user) {
            return Inertia::render("auth/$this->role/UserDetails", [
                'user' => $user,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function requestResetPasswordCode(RequestResetPasswordRequest $request)
    {
        $result = $this->userService->passwordResetRequest($request->validated()['email']);
        if ($result) {
            return Inertia::render("auth/$this->role/ResetPasswordCodeForm");
        } else {
            return redirect()->back();
        }
    }

    public function validateResetPasswordCode(CheckPasswordResetRequest $request)
    {
        $request->validated();
        return redirect()->route("v1.web.public.$this->role.reset.password.page");
    }

    public function changePassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService->passwordReset($data['reset_password_code'], $data['password']);

        if ($result) {
            return redirect()->route("v1.web.public.$this->role.login.page");
        } else {
            return redirect()->back();
        }
    }

    public function register(AuthRegisterRequest $request)
    {
        $user = $this->userService->register($request->validated(), $this->role, $this->relations);
        if ($user) {
            return redirect()->route("v1.web.$this->role.user.details");
        } else {
            return redirect()->back();
        }
    }

    public function logout()
    {
        $this->userService->logout();
        return redirect()->route("v1.web.public.$this->role.login.page");
    }
}
