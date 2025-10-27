<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function showLinkRequestForm()
    {
        return view('frontend.user.auth.reset');
    }


    protected function validateEmail(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
        ];

        if (getSetting('captcha_rest_password')) {

            if (getSetting('captcha') == 'hcaptcha' && isPluginEnabled('hcaptcha')) {
                $rules['h-captcha-response'] = 'required|hcaptcha';
            } elseif (getSetting('captcha') == 'recaptcha' && isPluginEnabled('recaptcha')) {
                $rules['g-recaptcha-response'] = 'captcha';
            }
        }

        $request->validate($rules);
    }
}