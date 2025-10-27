<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;



class GeneralController extends Controller
{
    public function index()
    {
        return view('admin.settings.general');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|max:255',
            'site_url' => 'required|url',
            'default_language' => 'required|in:' . getAllLanguagesValidation(),
            'timezone' => 'required',
            'privacy_policy' => 'required|url',
            'terms_of_service' => 'required|url',
            'cookie_policy' => 'required|url',
            'call_to_action' => 'required|url',
        ]);

        $request->site_url = rtrim($request->site_url, '/');

        $settings = Setting::whereIn(
            'key',
            [
                'site_name',
                'site_url',
                'default_language',
                'timezone',
                'privacy_policy',
                'site_url',
                'terms_of_service',
                'cookie_policy',
                'enable_preloader',
                'hide_default_lang',
                'enable_cookie',
                'https_force',
                'enable_verification',
                'enable_registration',
                'cookie_policy',
                'call_to_action'
            ]
        )->get();


        foreach ($settings as $setting) {
            $key = $setting->key;
            // $setting->value = $request->$key;
            // $setting->save();
            setSetting($key, $request->$key);
        }

        updateEnvFile('APP_NAME', $request->site_name);
        updateEnvFile('APP_URL', $request->site_url);
        updateEnvFile('APP_TIMEZONE', $request->timezone);
        updateEnvFile('DEFAULT_LANG', $request->default_language);

        updateEnvFile('HIDE_DEFAULT_LANG_IN_URL', $request->hide_default_lang ? "true" : "false");
        updateEnvFile('HTTPS_FORCE', $request->https_force ? "true" : "false");

        showToastr(__('lobage.toastr.update'));
        return back();
    }
}
