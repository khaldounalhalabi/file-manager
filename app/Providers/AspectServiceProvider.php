<?php

namespace App\Providers;

use App\Aspects\Contracts\AspectWrapper;
use App\Aspects\FileLoggingAspect;
use App\Services\v1\File\FileService;
use Illuminate\Support\ServiceProvider;

class AspectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AspectWrapper::class, function ($app) {
            $target = FileService::make();
            $aspect = new FileLoggingAspect();

            return new AspectWrapper($target, $aspect);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
