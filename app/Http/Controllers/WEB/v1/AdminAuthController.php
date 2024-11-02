<?php

namespace App\Http\Controllers\WEB\v1;

use App\Enums\RolesPermissionEnum;

class AdminAuthController extends BaseAuthController
{
    public function __construct()
    {
        $this->roleHook(RolesPermissionEnum::ADMIN['role']);
        parent::__construct();
    }
}
