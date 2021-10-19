<?php
   
namespace App\Http\Middleware;
   
use Closure;
   
class IpMiddleware
{
    
    public $restrictIps = [
        '162.158.94.132',
    ];
        
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->ip(), $this->restrictIps)) {
    
            abort(403, 'Vous avez été banni de Blinest.');

        }
    
        return $next($request);
    }
}