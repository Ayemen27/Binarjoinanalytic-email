<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Menu;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\InstallService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;


class InstallController extends Controller
{
    protected $installService;

    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    public function welcome()
    {
        //dd('f');

        if (env('INSTALL_WELCOME')) {
            return redirect()->route('install.requirements');
        }




        return view('install.welcome', ['currentStep' => 0]);
    }


    public function welcomePost()
    {
        Artisan::call('key:generate');
        updateEnvFile('INSTALL_WELCOME', '1');
        updateEnvFile('DEMO_MODE', 'false');
        updateEnvFile('APP_IN_DEV_MODE', 'false');


        return redirect()->route('install.requirements');
    }



    public function requirements()
    {

        if (env('INSTALL_REQUIREMENTS')) {
            return redirect()->route('install.filePermissions');
        }


        $requirements = $this->installService->checkRequirements();

        $phpVersionPass = $requirements['php_version'];
        $allExtensionsPass = !in_array(false, $requirements['extensions']);

        $allRequirementsPass = $phpVersionPass && $allExtensionsPass;

        $currentStep = 1;

        return view('install.requirements', compact('requirements', 'allRequirementsPass', 'currentStep'));
    }



    public function requirementsPost(Request $request)
    {


        $requirements = $this->installService->checkRequirements();

        $phpVersionPass = $requirements['php_version'];
        $allExtensionsPass = !in_array(false, $requirements['extensions']);

        $allRequirementsPass = $phpVersionPass && $allExtensionsPass;

        if (!$allRequirementsPass) {
            return redirect()->route('install.requirements');
        }

        updateEnvFile('INSTALL_REQUIREMENTS', 1);

        return redirect()->route('install.filePermissions');
    }



    public function filePermissions()
    {
        if (env('INSTALL_FILE_PERMISSIONS')) {
            return redirect()->route('install.license');
        }


        $permissions = $this->installService->checkFilePermissions();

        $allPermissionsPass = !in_array(false, $permissions);

        $currentStep = 2;

        return view('install.file_permissions', compact('permissions', 'allPermissionsPass', 'currentStep'));
    }

    public function filePermissionsPost(Request $request)
    {
        updateEnvFile('INSTALL_FILE_PERMISSIONS', '1');
        return redirect()->route('install.license');
    }


    public function license()
    {
        if (env('INSTALL_LICENSE')) {
            return redirect()->route('install.databaseInfo');
        }
        return view('install.license', ['currentStep' => 3]);
    }


    public function licensePost(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
        ]);


        try {
            // Process the license key
            $data = $this->installService->checkLicense($request->input('purchase_code'));

            dump($data);

            if ($data === null) {
                $data = $this->installService->checkLicense($request->input('purchase_code'), true);
            }
            dump($data);

            if (!$data['status'] || $data === null) {
                return back()
                    ->withErrors(['message' => $data['message']])
                    ->with('action', $data['action'] ?? false);
            }

            $jsonData = json_encode($data['data'], JSON_PRETTY_PRINT);

            // Define the file name with extension
            $filename = 'license.json';

            File::put($filename, $jsonData);

            updateEnvFile('INSTALL_LICENSE', '1');

            return redirect()->route('install.databaseInfo');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }




    public function databaseInfo()
    {
        if (env('INSTALL_DATABASE_INFO')) {
            return redirect()->route('install.databaseImport');
        }

        return view('install.database_info', ['currentStep' => 4]);
    }

    public function databaseInfoPost(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string|regex:/^[^#]*$/', // Exclude #
            'db_name' => 'required|string|regex:/^[^#]*$/', // Exclude #
            'db_username' => 'required|string|regex:/^[^#]*$/', // Exclude #
            'db_password' => 'nullable|string|regex:/^[^#]*$/', // Exclude #
        ]);



        // Check if cURL is installed
        if (!function_exists('curl_version')) {
            return back()->withErrors(['error' => "cURL is not installed on this server. Please contact your server administrator."])->withInput();
        }

        // Check if the .env file is writable
        if (!is_writable(base_path('.env'))) {
            return back()->withErrors(['error' => "The .env file is not writable. Please ensure the file permissions allow writing."])->withInput();
        }

        $dbHost = $request->input('db_host');
        $dbName = $request->input('db_name');
        $dbUsername = $request->input('db_username');
        $dbPassword = $request->input('db_password');

        try {
            // Temporarily set a new database connection
            config([
                'database.connections.temp_db' => [
                    'driver' => 'mysql',
                    'host' => $dbHost,
                    'port' => env('DB_PORT', 3306),
                    'database' => $dbName,
                    'username' => $dbUsername,
                    'password' => $dbPassword,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'strict' => false,
                    'engine' => null,
                ],
            ]);

            // Attempt to connect
            DB::connection('temp_db')->getPdo();
        } catch (Exception $e) {
            return back()->withErrors(['error' => "Unable to connect to the database: " . $e->getMessage()])->withInput();
        }

        updateEnvFile('DB_HOST', $dbHost);
        updateEnvFile('DB_DATABASE', $dbName);
        updateEnvFile('DB_USERNAME', $dbUsername);
        updateEnvFile('DB_PASSWORD', $dbPassword);


        updateEnvFile('INSTALL_DATABASE_INFO', '1');

        return redirect()->route('install.databaseImport');
    }

    public function databaseImport()
    {
        if (env('INSTALL_DATABASE_IMPORT')) {
            return redirect()->route('install.siteInfo');
        }

        return view('install.database_import', ['currentStep' => 5]);
    }

    public function databaseImportPost(Request $request)
    {

        //return redirect()->back()->withErrors(['skip' => "Unable to Import the database"]);


        if (!file_exists(database_path('data.sql'))) {
            return back()->withErrors(['error' => "SQL file is missing"]);
        }


        try {
            $sql_path = database_path('data.sql');
            DB::unprepared(file_get_contents($sql_path));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['skip' => "Unable to Import the database" . $e->getMessage()]);
        }



        // Update environment variable upon completion
        updateEnvFile('INSTALL_DATABASE_IMPORT', '1');

        return redirect()->route('install.siteInfo');
    }


    public function download()
    {
        if (env('INSTALL_DATABASE_IMPORT')) {
            return redirect()->route('install.siteInfo');
        }

        return response()->download(database_path('data.sql'));
    }


    public function skip()
    {
        if (env('INSTALL_DATABASE_IMPORT')) {
            return redirect()->route('install.siteInfo');
        }

        try {
            getSetting('site_name');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['skip' => __('You need to import the database first')]);
        }

        updateEnvFile('INSTALL_DATABASE_IMPORT', '1');

        return redirect()->route('install.siteInfo');
    }


    public function siteInfo()
    {
        if (env('INSTALL_SITE_INFO')) {
            return redirect()->route('install.complete');
        }

        return view('install.site_info', ['currentStep' => 6]);
    }


    public function siteInfoPost(Request $request)
    {

        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'admin_email' => 'required|unique:admins,email',
            'admin_password' => 'required|string|min:8',
            'admin_path' => 'required|string|alpha_dash',
        ]);

        //dd($request->admin_path);

        setSetting('site_name', $request->site_name);
        setSetting('site_url', $request->site_url);
        setSetting('api_key', Str::random(30));
        setSetting('cronjob_key', Str::random(30));
        updateEnvFile('APP_URL', $request->site_url);
        updateEnvFile('APP_NAME', $request->site_name);
        updateEnvFile('ADMIN_PATH', $request->admin_path);


        $register = Admin::create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'avatar' => config('lobage.default_avatar'),
        ]);

        updateEnvFile('INSTALL_SITE_INFO', 1);

        return redirect()->route('install.complete');
    }

    public function complete()
    {
        if (env('SYSTEM_INSTALLED')) {
            return redirect()->route('admin.login');
        }

        Menu::create([
            'name' => 'Blog',
            'url' => url("/blog"),
            'type' => 1,
            'lang' => "en",
            'is_external' => 0
        ]);

        Menu::create([
            'name' => 'Contact Us',
            'url' => url("/contact"),
            'type' => 1,
            'lang' => "en",
            'is_external' => 0
        ]);

        updateEnvFile('SYSTEM_INSTALLED', 1);
        updateEnvFile('APP_DEBUG', 'false');
        updateEnvFile('APP_ENV', 'production');
        updateEnvFile('MAINTENANCE_MODE', 'false');

        return view('install.complete', ['currentStep' => 7]);
    }
}
