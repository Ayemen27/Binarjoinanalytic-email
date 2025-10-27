<?php

namespace App\Http\Controllers\Frontend\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function guard()
    {
        return Auth::guard('web');
    }



    public function showLoginForm()
    {
        return view('frontend.user.auth.login');
    }

    protected function validateLogin(Request $request)
    {

        $rules = [
            $this->username() => 'required|string|email',
            'password' => 'required|string',
        ];

        if (getSetting('captcha_login')) {

            if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha')) {
                $rules['h-captcha-response'] = 'required|hcaptcha';
            } elseif (getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha')) {
                $rules['g-recaptcha-response'] = 'captcha';
            }
        }

        // Validate the request with the defined rules
        $request->validate($rules);
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