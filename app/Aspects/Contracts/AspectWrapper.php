<?php

namespace App\Aspects\Contracts;

use App\Services\Contracts\BaseService;

class AspectWrapper
{
    protected BaseService $target;
    protected Aspect $aspect;

    public function __construct($target, Aspect $aspect)
    {
        $this->target = $target;
        $this->aspect = $aspect;
    }

    public function __call(string $method, array $arguments)
    {
        $this->aspect->before($method, $arguments);

        $result = $this->aspect->around($method, $arguments, function () use ($method, $arguments) {
            return $this->target->{$method}(...$arguments);
        });

        $this->aspect->after($method, $arguments, $result);

        return $result;
    }
}
