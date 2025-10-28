<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class EnsureDatabaseConnection
{
    /**
     * Handle an incoming request and ensure database connection is available
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for install routes and static assets
        if ($request->is('install/*') || 
            $request->is('assets/*') || 
            $request->is('storage/*') || 
            $request->is('build/*') ||
            $request->is('system-diagnostics*')) {
            return $next($request);
        }

        // Only check if system is installed
        if (isSystemInstalled()) {
            try {
                // Test database connection
                DB::connection()->getPdo();
            } catch (Exception $e) {
                // Log the error
                error_log("Database connection failed: " . $e->getMessage());
                
                // Redirect to diagnostics page with error message
                return redirect()->route('system.diagnostics')
                    ->with('error', 'فشل الاتصال بقاعدة البيانات. يرجى التحقق من الإعدادات.');
            }
        }

        return $next($request);
    }
}
