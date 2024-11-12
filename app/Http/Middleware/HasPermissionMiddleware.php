<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiController;
use App\Traits\RestTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermissionMiddleware
{
    use RestTrait;

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @param string  $permission
     * @param string  $model
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $permission, string $model): Response
    {
        if ($request->expectsJson() && !auth('api')->user()?->hasPermission($permission, $model)) {
            return $this->apiResponse(null, ApiController::STATUS_FORBIDDEN, __('site.unauthorized_user'));
        } elseif (!$request->expectsJson() && !auth('web')->user()?->hasPermission($permission, $model)) {
            abort(403, __('site.unauthorized_user'));
        }
        return $next($request);
    }
}
