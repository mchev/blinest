<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsPublicModerator
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->isPublicModerator() || $request->user()->isAdministrator()) {
            return $next($request);
        }

        return abort('403');
    }
}
