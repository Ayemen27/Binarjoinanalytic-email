<?php

namespace App\Http\Controllers\Admin\Settings;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SystemInfoController extends Controller
{
    public function show()
    {
        // Get the system info
        $phpVersion = is_demo() ? 'Hidden-in-demo' : phpversion();
        $laravelVersion = is_demo() ? 'Hidden-in-demo' : App::version();
        $serverSoftware = is_demo() ? 'Hidden-in-demo' : ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A');
        $operatingSystem = is_demo() ? 'Hidden-in-demo' : php_uname();
        $timezone = is_demo() ? 'Hidden-in-demo' : config('app.timezone');
        $memoryUsage = is_demo() ? 'Hidden-in-demo' : round(memory_get_usage() / 1024 / 1024, 2) . ' MB';
        $maxUploadSize = is_demo() ? 'Hidden-in-demo' : ini_get('upload_max_filesize');
        $maxExecutionTime = is_demo() ? 'Hidden-in-demo' : ini_get('max_execution_time');
        $diskFreeSpace = is_demo() ? 'Hidden-in-demo' : round(disk_free_space("/") / 1024 / 1024 / 1024, 2) . ' GB';
        $diskTotalSpace = is_demo() ? 'Hidden-in-demo' : round(disk_total_space("/") / 1024 / 1024 / 1024, 2) . ' GB';
        $dbConnection = is_demo() ? 'Hidden-in-demo' : config('database.default');


        // Corrected Database version
        if (is_demo()) {
            $dbVersion =  'Hidden-in-demo';
        } else {
            $dbVersion =  DB::select("SELECT version() AS version")[0]->version ?? 'N/A';
        }


        return view('admin.settings.system-info', compact(
            'phpVersion',
            'laravelVersion',
            'serverSoftware',
            'operatingSystem',
            'timezone',
            'memoryUsage',
            'maxUploadSize',
            'maxExecutionTime',
            'diskFreeSpace',
            'diskTotalSpace',
            'dbConnection',
            'dbVersion'
        ));
    }
}
