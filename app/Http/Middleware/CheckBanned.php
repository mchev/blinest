<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Closure;

class CheckBanned
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


        // PREVENT FOR REGISTERING
        if (auth()->guest() && $request->route()->getName() == "register") {

            $user = \App\User::where('last_login_ip', $request->getClientIp())->select('id', 'banned_until', 'last_login_ip')->first();

            if ($user !== null && $user->banned_until && now()->lessThan($user->banned_until)) {
                $message = 'Les comptes associés à cette adresse IP ont été bannis par nos modérateurs. Vous ne pouvez pas vous inscrire.';
                return redirect('/')->withMessage($message);
            }

        }

        // PREVENT FOR LOGIN
        else if (auth()->check() && auth()->user()->banned_until && now()->lessThan(auth()->user()->banned_until)) {
            $banned_days = now()->diffInDays(auth()->user()->banned_until);
            auth()->logout();

            if ($banned_days > 14) {
                $message = 'Ce compte a été suspendu.';
            } else {
                $message = 'Ce compte a été suspendu pour '.$banned_days.' '.Str::plural('jour', $banned_days) . '.';
            }

            return redirect()->route('login')->withMessage($message);
        }


        return $next($request);
    }
}
