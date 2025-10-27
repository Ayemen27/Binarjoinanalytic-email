<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanRegisterUser
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

        // Check the setting in the database
        $registrationEnabled = getSetting('enable_registration');

        if (!$registrationEnabled) {
            // Registration is disabled, perform desired action
            return redirect()->route('index');
        }

        return $next($request);
    }
}
