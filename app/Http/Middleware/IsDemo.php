<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // List of routes that are exempt from demo mode restrictions
        $exemptRoutes = [
            'admin.settings.check.imap',
            'admin.settings.check.imap2',
            // Add more route names as needed
        ];

        // Get the current route name
        $currentRouteName = $request->route()->getName();

        // Restrict POST, PUT, PATCH, DELETE methods if demo mode is enabled and the route is not exempt
        if (is_demo() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && !in_array($currentRouteName, $exemptRoutes)) {
            return back()->with('error', 'Demo version some features are disabled');
        }

        return $next($request);
    }
}
