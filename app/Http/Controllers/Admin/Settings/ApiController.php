<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ApiController extends Controller
{
    public function index()
    {
        return view('admin.settings.api');
    }


    public function update(Request $request)
    {

        $request->validate([
            'enable_api' => 'required',
            'api_key' => 'required',
        ]);

        $settings = Setting::whereIn(
            'key',
            [
                'enable_api',
                'api_key',
            ]
        )->get();

        foreach ($settings as $setting) {
            $key = $setting->key;
            setSetting($key, $request->$key);
        }
        showToastr(__('lobage.toastr.update'));
        return back();
    }
}
