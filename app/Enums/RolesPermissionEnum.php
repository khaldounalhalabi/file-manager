<?php

namespace App\Enums;

/**
 * Class RolesPermissionEnum
 */
class RolesPermissionEnum
{
    //**********ADMIN***********//
    const ADMIN = [
        'role' => 'admin',
        'permissions' => [],
    ];
    //*************************//

    const ALLROLES = [
        self::ADMIN['role'],
        self::CUSTOMER['role'],
        //add-all-your-enums-roles-here

    ];

    const ALL = [
        self::ADMIN,
        self::CUSTOMER,
        //add-all-your-enums-here

    ];

    //**********CUSTOMER***********//
    const CUSTOMER = [
        'role' => 'customer',
        'permissions' => [],
    ];
    //*************************//

}
