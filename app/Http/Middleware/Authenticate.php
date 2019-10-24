<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
		$previousURL  =  $request->fullUrl();
		$request->session()->put('old_url', $previousURL);
        if (! $request->expectsJson()) {
            return url('/signin');
        }
    }
}
