<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (env('SYSTEM_INSTALLED') != "1") {
            // Allow static assets to be loaded during installation
            if ($request->is('assets/*') || 
                $request->is('storage/*') || 
                $request->is('build/*') ||
                $request->is('favicon.ico') ||
                $request->is('uploads/*')) {
                return $next($request);
            }
            
            // Redirect to the installation route
            return redirect()->route('install.index'); // Make sure this route exists in your application
        }

        return $next($request);
    }
}
