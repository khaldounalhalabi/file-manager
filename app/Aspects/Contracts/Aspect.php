<?php

namespace App\Aspects\Contracts;

use Closure;

abstract class Aspect
{
    public function before(string $method, array $args)
    {

    }

    public function after(string $method, array $args, $result)
    {

    }

    public function around(string $method, array $args, Closure $proceed)
    {
        return $proceed();
    }
}
