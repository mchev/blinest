<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIsPublicModerator
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isPublicModerator()) {
            return $next($request);
        }

        return abort('403');
    }
}
