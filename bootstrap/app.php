<?php
use App\Http\Middleware\AcceptedLanguagesMiddleware ;

use Illuminate\Http\Request;

use App\Exceptions\Handler;

use App\Http\Middleware\Authenticate;


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
api: __DIR__.'/../routes/api.php',
commands: __DIR__.'/../routes/console.php',
health: '/up',
then: function () {

            Route::middleware(['web',
                'locale',
            ])
                ->group(base_path('routes\v1\web\public.php'));

            Route::middleware(['web', 'locale'])
                ->group(base_path('routes\v1\web\protected.php'));

            Route::middleware(['web', 'locale', 'authenticated', 'has-role:admin'])
                ->group(base_path('routes\v1\web\admin.php'));

            Route::middleware(['web', 'locale', 'authenticated', 'has-role:customer'])
                ->group(base_path('routes\v1\web\customer.php'));

Route::middleware(['api' ,'locale', ])
->prefix('api')
->group(base_path('routes\v1\api\public.php'));

Route::middleware(['api' ,'locale', 'authenticated'])
->prefix('api')
->group(base_path('routes\v1\api\protected.php'));





        }
    )
    ->withMiddleware(function (Middleware $middleware) {
//
        $middleware->alias([
'has-role' => HasRoleMiddleware::class,
'has-permission' => HasPermissionMiddleware::class,
'authenticated' => Authenticate::class
,
'locale' => AcceptedLanguagesMiddleware::class
]);

})->withExceptions(function (Exceptions $exceptions) {
//

        if(!request()->acceptsHtml()){
            $exceptions->render(function (Exception $exception, Request $request) {
                $handler = new Handler();
                return $handler->handleException($request, $exception);
            });
        }

})->create();
