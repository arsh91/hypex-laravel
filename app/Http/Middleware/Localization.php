<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App;

class Localization
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
        if(session()->has('locale')){
            //echo session()->get('locale'); die;
            App::setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}
