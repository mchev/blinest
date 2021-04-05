<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain;

class PreferredDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $prefDomain = new Domain($request);

        if ($prefDomain->diff()) {
            return redirect()->to($prefDomain->translated(), 301);
        }
        return $next($request);
    }
}