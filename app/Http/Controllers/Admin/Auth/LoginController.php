<?php

namespace App\Http\Controllers\Admin\Auth;

use Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->redirectTo = config('lobage.admin_path');
    }


    public function showLoginForm()
    {

        return view('admin.auth.login');
    }


    protected function guard()
    {
        return Auth::guard('admin');
    }

    /*
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    */

    protected function validateLogin(Request $request)
    {

        $rules = [
            $this->username() => 'required|string|email',
            'password' => 'required|string',
        ];

        if (getSetting('captcha_admin')) {

            if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha')) {
                $rules['h-captcha-response'] = 'required|hcaptcha';
            }

            if (getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha')) {
                $rules['g-recaptcha-response'] = 'captcha';
            }
        }

        // Validate the request with the defined rules
        $request->validate($rules);
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            if ($request->hasSession()) {
                $request->session()->put('auth.admin_password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }



    public function logout(Request $request)
    {
        $sessionKey = $this->guard()->getName();

        $this->guard()->logout();

        $request->session()->forget($sessionKey);

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect($this->redirectTo);
    }
}
