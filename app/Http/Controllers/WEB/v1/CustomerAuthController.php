<?php

namespace App\Http\Controllers\WEB\v1;

use App\Enums\RolesPermissionEnum;
use App\Http\Requests\v1\AuthRequests\AuthLoginRequest;

class CustomerAuthController extends BaseAuthController
{
    public function __construct()
    {
        $this->roleHook(RolesPermissionEnum::CUSTOMER['role']);
        parent::__construct();
    }

    public function login(AuthLoginRequest $request)
    {
        $user = $this->userService->login($request->validated(), $this->role, [], $this->relations);

        if ($user) {
            if ($user->group_id) {
                return redirect()->route("v1.web.$this->role.user.details");
            } else {
                return redirect()->route('v1.web.customer.user.groups');
            }
        } else {
            return redirect()->back();
        }
    }
}
