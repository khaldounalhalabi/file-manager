<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiController;
use App\Traits\RestTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMustHaveAGroup
{
    use RestTrait;

    /**
     * Handle an incoming request.
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->isCustomer() && !auth()->user()->group_id) {
            if ($request->hasHeader('X-Source') && $request->header('X-Source') == "Fetch-Api") {
                return $this->apiResponse(false, ApiController::STATUS_NO_GROUP, "You have no group");
            } else {
                return redirect()->route('v1.web.customer.user.groups');
            }
        }
        return $next($request);
    }
}
