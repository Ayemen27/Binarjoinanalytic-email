<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class CaptchaController extends Controller
{
    public function index()
    {
        return view('admin.settings.captcha');
    }

    public function update(Request $request)
    {
        $request->validate([
            'captcha' => 'required|max:255',
            'captcha_login' => 'nullable|boolean',
            'captcha_register' => 'nullable|boolean',
            'captcha_contact' => 'nullable|boolean',
            'captcha_rest_password' => 'nullable|boolean',
            'captcha_admin' => 'nullable|boolean',
        ]);

        // Automate conversion of boolean fields
        $booleanFields = [
            'captcha_login',
            'captcha_register',
            'captcha_contact',
            'captcha_rest_password',
            'captcha_admin'
        ];

        foreach ($booleanFields as $field) {
            $request[$field] = $request->has($field) ? 1 : 0;
        }

        $settings = Setting::whereIn('key', [
            'captcha',
            'captcha_login',
            'captcha_register',
            'captcha_contact',
            'captcha_rest_password',
            'captcha_admin'
        ])->get();
        foreach ($settings as $setting) {
            $key = $setting->key;
            setSetting($key, $request->$key);
            // $setting->value = $request->$key;
            // $setting->save();
        }

        showToastr(__('lobage.toastr.update'));
        return back();
    }
}