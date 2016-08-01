<?php

namespace App\Http\Middleware;

use Closure;
use App\models\Club;

class clubAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$access)
    {
		$club=Club::getByShortName($request->club);
		if(count($club)!==1)
		{
			return response()->view('errors.404');
		}
        else if (auth()->check() && auth()->user()->hasPermission($access,$club->club_id)) 
		{
			 return $next($request);
		}
		return response()->view('errors.401');
        
    }
}
