<?php

use App\Exceptions\Handler;
use App\Http\Middleware\AcceptedLanguagesMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CustomerMustHaveAGroup;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HasPermissionMiddleware;
use App\Http\Middleware\HasRoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'locale'])
                ->group(base_path('routes/v1/web/public.php'));

            Route::middleware(['web', 'locale'])
                ->group(base_path('routes/v1/web/protected.php'));

            Route::middleware(['web', 'locale', 'authenticated', 'has-role:admin'])
                ->name('v1.web.admin.')
                ->prefix('v1/admin')
                ->group(base_path('routes/v1/web/admin.php'));

            Route::middleware(['web', 'locale', 'authenticated', 'has-role:customer', 'must-have-group'])
                ->name('v1.web.customer.')
                ->prefix('v1/customer')
                ->group(base_path('routes/v1/web/customer.php'));
        }
    )->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'has-role' => HasRoleMiddleware::class,
            'has-permission' => HasPermissionMiddleware::class,
            'authenticated' => Authenticate::class,
            'locale' => AcceptedLanguagesMiddleware::class,
            'must-have-group' => CustomerMustHaveAGroup::class
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

    })->withExceptions(function (Exceptions $exceptions) {
        if (!request()->acceptsHtml()) {
            $exceptions->render(function (Exception $exception, Request $request) {
                $handler = new Handler();
                return $handler->handleException($request, $exception);
            });
        }
    })->create();
