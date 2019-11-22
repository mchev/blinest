<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class IsAdmin
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

        if(Auth::check() && auth()->user()->is('admin')) {
            return $next($request);
        }
        return redirect('/login');
    }
}
