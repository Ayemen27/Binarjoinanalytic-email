<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAccessIfInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the system is already installed
        if (env('SYSTEM_INSTALLED') == "1") {
            // Redirect to home or dashboard to prevent accessing installation
            return redirect()->route('index'); // Adjust this to your main page route
        }

        return $next($request);
    }
}
