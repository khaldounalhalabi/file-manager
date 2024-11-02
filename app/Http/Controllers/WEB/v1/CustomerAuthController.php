<?php

namespace App\Http\Controllers\WEB\v1;

use App\Enums\RolesPermissionEnum;

class CustomerAuthController extends BaseAuthController
{
    public function __construct()
    {
        $this->roleHook(RolesPermissionEnum::CUSTOMER['role']);
        parent::__construct();
    }
}
