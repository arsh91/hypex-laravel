<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Closure;

class AuthCheckRedirectMiddleware
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
		$previousURL  =  $request->fullUrl();
		$request->session()->put('old_url', $previousURL);
		return $next($request);
    }
}
