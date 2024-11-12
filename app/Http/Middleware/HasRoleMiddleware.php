<?php

namespace App\Http\Middleware;

use App\Traits\RestTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasRoleMiddleware
{
    use RestTrait;

    /**
     * Handle an incoming request.
     * @param Request                      $request
     * @param Closure(Request): (Response) $next
     * @param string                       $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($request->expectsJson() && !auth('api')?->user()?->hasRole($role)) {
            return response()->json([
                'data' => null,
                'status' => false,
                'code' => 403,
                'message' => __('site.unauthorized_user')
            ]);
        } elseif (!$request->expectsJson() && !auth('web')?->user()?->hasRole($role)) {
            return redirect()->route("v1.web.public.$role.login.page");
        }

        return $next($request);
    }
}
