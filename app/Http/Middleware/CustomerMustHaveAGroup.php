<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMustHaveAGroup
{
    /**
     * Handle an incoming request.
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->isCustomer() && !auth()->user()->group_id) {
            return redirect()->route('v1.web.customer.user.groups');
        }
        return $next($request);
    }
}
