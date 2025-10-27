<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CronJobController extends Controller
{
    public function index()
    {
        return view('admin.settings.cronjob');
    }


    public function update()
    {
        $apiKey = Str::random(30);
        setSetting('cronjob_key', $apiKey);
        showToastr(__('lobage.toastr.update'));
        return back();
    }
}