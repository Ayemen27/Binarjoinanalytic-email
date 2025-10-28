<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ========================================
// REPLIT DATABASE CONNECTION LOADER
// ========================================
// This section ensures database credentials persist across Replit restarts
// by loading them from install_state.json when the system is installed

$installStatePath = __DIR__ . '/../storage/app/install_state.json';

if (file_exists($installStatePath)) {
    try {
        $installStateContent = file_get_contents($installStatePath);
        $installState = json_decode($installStateContent, true);
        
        // Check if JSON is valid and system is installed
        if (json_last_error() === JSON_ERROR_NONE && 
            isset($installState['SYSTEM_INSTALLED']) && 
            $installState['SYSTEM_INSTALLED'] === '1') {
            
            // Verify all required database keys exist
            $requiredKeys = ['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
            $allKeysPresent = true;
            
            foreach ($requiredKeys as $key) {
                if (!isset($installState[$key])) {
                    $allKeysPresent = false;
                    error_log("Missing database key in install_state.json: {$key}");
                    break;
                }
            }
            
            // If all keys are present, inject them into environment
            if ($allKeysPresent) {
                $_ENV['DB_CONNECTION'] = $installState['DB_CONNECTION'];
                $_ENV['DB_HOST'] = $installState['DB_HOST'];
                $_ENV['DB_PORT'] = $installState['DB_PORT'];
                $_ENV['DB_DATABASE'] = $installState['DB_DATABASE'];
                $_ENV['DB_USERNAME'] = $installState['DB_USERNAME'];
                $_ENV['DB_PASSWORD'] = $installState['DB_PASSWORD'];
                
                putenv("DB_CONNECTION={$installState['DB_CONNECTION']}");
                putenv("DB_HOST={$installState['DB_HOST']}");
                putenv("DB_PORT={$installState['DB_PORT']}");
                putenv("DB_DATABASE={$installState['DB_DATABASE']}");
                putenv("DB_USERNAME={$installState['DB_USERNAME']}");
                putenv("DB_PASSWORD={$installState['DB_PASSWORD']}");
                
                error_log("✓ Database credentials loaded from install_state.json");
            } else {
                error_log("⚠ Some database keys missing in install_state.json, falling back to .env");
            }
        }
    } catch (\Exception $e) {
        // Log error but don't crash - fallback to .env values
        error_log("⚠ Error reading install_state.json: " . $e->getMessage());
        error_log("Falling back to .env database configuration");
    }
}

// ========================================

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        //web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        //health: '/up',
        then: function () {
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->prefix(env('APP_DIR', ''))
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->namespace('App\Http\Controllers')
                ->prefix(env('APP_DIR') . '/api')
                ->group(base_path('routes/api.php'));
        },

    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin'                    => \App\Http\Middleware\IsAdmin::class,
            'verified'                 => \App\Http\Middleware\CheckEmailVerification::class,
            'prevent-installed-access' => \App\Http\Middleware\PreventAccessIfInstalled::class,
            'not-installed'             => \App\Http\Middleware\RedirectIfNotInstalled::class,
            //'subscribe'               => \App\Http\Middleware\CheckIsSubscribe::class,
            'checkRegister'            => \App\Http\Middleware\CanRegisterUser::class,
            'checkBan'                 => \App\Http\Middleware\CheckBanned::class,
            'localize'                 => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'     => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect'    => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'     => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'           => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'trustip' => \App\Http\Middleware\Trustip::class,
            'blog.enabled' => \App\Http\Middleware\CheckBlogEnabled::class,
            'api.enabled' => \App\Http\Middleware\ApiMiddleware::class,
            'demo' => \App\Http\Middleware\IsDemo::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class,

        ]);



        $middleware->redirectGuestsTo(function (Request $request) {
            $guard = 'web'; // default guard

            // dd($request->is('admin'));
            // Determine the guard based on the route prefix or other logic
            if ($request->is(env('ADMIN_PATH') . '/*') or $request->is(env('ADMIN_PATH'))) {
                $guard = 'admin';
            }


            //dd( $guard);

            // Redirect based on the guard
            switch ($guard) {
                case 'admin':
                    return route('admin.login');
                default:
                    return route('login');
            }
        });





        $middleware->validateCsrfTokens(except: [
            'get_messages',
            'install/*',
        ]);




        $middleware->group('mcamara', [
            'localeCookieRedirect',
            'localizationRedirect',
            'localeViewPath',
        ]);

        $middleware->validateCsrfTokens(except: [
            'delete',

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
