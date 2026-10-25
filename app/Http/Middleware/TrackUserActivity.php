<?php

namespace App\Http\Middleware;

use App\Services\UserActivityService;
use Closure;
use Illuminate\Http\Request;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $audit = app(UserActivityService::class);
        if (!$request->session()->has('loginID') || !$audit->enabled()) return $next($request);
        $audit->actor($request);
        $response = $next($request);
        $audit->recordRequest($request, $response);

        return $response;
    }
}
