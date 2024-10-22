<?php

namespace App\Exceptions;

use Exception;

class RoleDoesNotExistException extends Exception
{

    public function __construct($roleName)
    {
        parent::__construct("$roleName Role Isn't Defined");
    }
}
