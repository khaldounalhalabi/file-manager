<?php

namespace App\Http\Middleware;

use App\Enums\RolesPermissionEnum;
use Closure;
use App\Traits\RestTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            if (!auth('api')?->user()) {
                return response()->json([
                    'data'      => null,
                    'status'    => false,
                    'code'      => 401,
                    'message'   => __('site.unauthorized_user')
                ]);
            }
        } elseif (!auth('web')?->user()) {
            if (str_contains($request->fullUrl() , RolesPermissionEnum::ADMIN['role'])){
                return redirect()->route('v1.web.public.admin.login.page');
            }else{
                return redirect()->route('v1.web.public.customer.login.page');
            }
        }

        return $next($request);
    }
}
