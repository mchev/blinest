<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isGuest()) {
            abort(404);
        }

        return $next($request);
    }
}
