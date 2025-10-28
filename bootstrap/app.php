<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
