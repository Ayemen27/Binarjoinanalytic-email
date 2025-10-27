<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MaintenanceController extends Controller
{

    public function index()
    {
        return view('admin.settings.maintenance');
    }

    public function update(Request $request)
    {

        //dd($request->title);
        setSetting('enable_maintenance', $request->enable_maintenance);
        setSetting('maintenance_title', $request->title);
        setSetting('maintenance_message', $request->message);
        updateEnvFile('APP_DEBUG', $request->app_debug);
        updateEnvFile('MAINTENANCE_MODE', $request->enable_maintenance);


        showToastr(__('lobage.toastr.update'));
        return redirect(route('admin.settings.maintenance'));
    }
}
