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

        if (getInstallState('INSTALL_WELCOME') == '1') {
            return redirect()->route('install.requirements');
        }




        return view('install.welcome', ['currentStep' => 0]);
    }


    public function welcomePost()
    {
        Artisan::call('key:generate');
        setInstallState('INSTALL_WELCOME', '1');
        updateEnvFile('DEMO_MODE', 'false');
        updateEnvFile('APP_IN_DEV_MODE', 'false');


        return redirect()->route('install.requirements');
    }



    public function requirements()
    {

        if (getInstallState('INSTALL_REQUIREMENTS') == '1') {
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

        setInstallState('INSTALL_REQUIREMENTS', '1');

        return redirect()->route('install.filePermissions');
    }



    public function filePermissions()
    {
        if (getInstallState('INSTALL_FILE_PERMISSIONS') == '1') {
            return redirect()->route('install.license');
        }


        $permissions = $this->installService->checkFilePermissions();

        $allPermissionsPass = !in_array(false, $permissions);

        $currentStep = 2;

        return view('install.file_permissions', compact('permissions', 'allPermissionsPass', 'currentStep'));
    }

    public function filePermissionsPost(Request $request)
    {
        setInstallState('INSTALL_FILE_PERMISSIONS', '1');
        return redirect()->route('install.license');
    }


    public function license()
    {
        if (getInstallState('INSTALL_LICENSE') == '1') {
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

            if (!isset($data['status']) || !$data['status'] || $data === null) {
                return back()
                    ->withErrors(['message' => $data['message'] ?? 'حدث خطأ أثناء التحقق من الترخيص'])
                    ->with('action', $data['action'] ?? false);
            }

            $jsonData = json_encode($data['data'], JSON_PRETTY_PRINT);

            // Define the file name with extension - stored in public folder
            $filename = public_path('license.json');

            File::put($filename, $jsonData);

            setInstallState('INSTALL_LICENSE', '1');

            return redirect()->route('install.databaseInfo');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }




    public function databaseInfo()
    {
        if (getInstallState('INSTALL_DATABASE_INFO') == '1') {
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
            // Get the database driver from environment (default to pgsql)
            $dbDriver = env('DB_CONNECTION', 'pgsql');
            
            // Read port from environment variables (PGPORT for PostgreSQL)
            $dbPort = getenv('PGPORT') ?: ($dbDriver === 'pgsql' ? 5432 : 3306);
            
            // Temporarily set a new database connection
            if ($dbDriver === 'pgsql') {
                config([
                    'database.connections.temp_db' => [
                        'driver' => 'pgsql',
                        'host' => $dbHost,
                        'port' => $dbPort,
                        'database' => $dbName,
                        'username' => $dbUsername,
                        'password' => $dbPassword,
                        'charset' => 'utf8',
                        'prefix' => '',
                        'schema' => 'public',
                        'sslmode' => 'prefer',
                    ],
                ]);
            } else {
                config([
                    'database.connections.temp_db' => [
                        'driver' => 'mysql',
                        'host' => $dbHost,
                        'port' => $dbPort,
                        'database' => $dbName,
                        'username' => $dbUsername,
                        'password' => $dbPassword,
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'strict' => false,
                        'engine' => null,
                    ],
                ]);
            }

            // Attempt to connect
            DB::connection('temp_db')->getPdo();
        } catch (Exception $e) {
            return back()->withErrors(['error' => "Unable to connect to the database: " . $e->getMessage()])->withInput();
        }

        // Store database credentials in .env file
        updateEnvFile('DB_HOST', $dbHost);
        updateEnvFile('DB_DATABASE', $dbName);
        updateEnvFile('DB_USERNAME', $dbUsername);
        updateEnvFile('DB_PASSWORD', $dbPassword);
        
        // Store database credentials redundantly in install state JSON for persistence across Replit restarts
        installState()->setMultiple([
            'DB_CONNECTION' => $dbDriver,
            'DB_HOST' => $dbHost,
            'DB_PORT' => (string) $dbPort,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUsername,
            'DB_PASSWORD' => $dbPassword,
        ]);
        
        // Update runtime configuration to use new credentials
        config([
            'database.connections.pgsql.host' => $dbHost,
            'database.connections.pgsql.port' => $dbPort,
            'database.connections.pgsql.database' => $dbName,
            'database.connections.pgsql.username' => $dbUsername,
            'database.connections.pgsql.password' => $dbPassword,
            'database.connections.mysql.host' => $dbHost,
            'database.connections.mysql.port' => $dbPort,
            'database.connections.mysql.database' => $dbName,
            'database.connections.mysql.username' => $dbUsername,
            'database.connections.mysql.password' => $dbPassword,
        ]);
        
        // Purge existing connection and reconnect with new credentials
        DB::purge($dbDriver);
        DB::reconnect($dbDriver);

        setInstallState('INSTALL_DATABASE_INFO', '1');

        return redirect()->route('install.databaseImport');
    }

    public function databaseImport()
    {
        if (getInstallState('INSTALL_DATABASE_IMPORT') == '1') {
            return redirect()->route('install.siteInfo');
        }

        // فحص وجود الجداول
        $tablesExist = false;
        $tableCount = 0;
        
        try {
            $dbDriver = env('DB_CONNECTION', 'pgsql');
            
            if ($dbDriver === 'pgsql') {
                $tableCount = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'")[0]->count;
            } else {
                $tableCount = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'")[0]->count;
            }
            
            $tablesExist = $tableCount > 0;
        } catch (Exception $e) {
            // في حالة فشل الفحص، نستمر بشكل طبيعي
        }

        return view('install.database_import', [
            'currentStep' => 5,
            'tablesExist' => $tablesExist,
            'tableCount' => $tableCount
        ]);
    }

    public function databaseImportPost(Request $request)
    {
        $request->validate([
            'db_type' => 'required|in:mysql,pgsql',
            'force_reimport' => 'nullable|boolean',
        ]);

        $dbType = $request->input('db_type');
        $forceReimport = $request->input('force_reimport', false);
        
        // التحقق من وجود الجداول
        $tablesExist = false;
        try {
            if ($dbType === 'pgsql') {
                $tableCount = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'")[0]->count;
            } else {
                $tableCount = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'")[0]->count;
            }
            $tablesExist = $tableCount > 0;
        } catch (Exception $e) {
            // متابعة في حالة فشل الفحص
        }
        
        // إذا كانت الجداول موجودة والمستخدم لم يختر إعادة الاستيراد، نتخطى
        if ($tablesExist && !$forceReimport) {
            setInstallState('INSTALL_DATABASE_IMPORT', '1');
            return redirect()->route('install.siteInfo');
        }
        
        // Determine the SQL file based on database type
        $sqlFileName = $dbType === 'pgsql' ? 'data_pgsql.sql' : 'data_mysql.sql';
        $sqlPath = database_path($sqlFileName);

        if (!file_exists($sqlPath)) {
            return back()->withErrors(['error' => "SQL file is missing: {$sqlFileName}"]);
        }

        try {
            // إذا كان المستخدم يريد إعادة الاستيراد، نحذف الجداول أولاً
            if ($forceReimport) {
                if ($dbType === 'pgsql') {
                    DB::statement('DROP SCHEMA public CASCADE');
                    DB::statement('CREATE SCHEMA public');
                    DB::statement('GRANT ALL ON SCHEMA public TO ' . env('DB_USERNAME'));
                    DB::statement('GRANT ALL ON SCHEMA public TO public');
                } else {
                    // للـ MySQL، نحصل على قائمة الجداول ونحذفها
                    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                    $tables = DB::select('SHOW TABLES');
                    $dbName = env('DB_DATABASE');
                    
                    foreach ($tables as $table) {
                        $tableName = $table->{"Tables_in_$dbName"};
                        DB::statement("DROP TABLE IF EXISTS `$tableName`");
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                }
            }
            
            DB::unprepared(file_get_contents($sqlPath));
            
            // إعادة تعيين sequences في PostgreSQL بعد الاستيراد لتجنب تعارض المفاتيح
            if ($dbType === 'pgsql') {
                $this->resetPostgresSequences();
            }
            
            // التحقق من وجود الجداول المهمة وإنشائها إذا كانت مفقودة
            $this->ensureCriticalTablesExist($dbType);
            
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['skip' => "Unable to Import the database: " . $e->getMessage()]);
        }

        // Update environment variable upon completion
        setInstallState('INSTALL_DATABASE_IMPORT', '1');

        return redirect()->route('install.siteInfo');
    }


    public function download()
    {
        if (getInstallState('INSTALL_DATABASE_IMPORT') == '1') {
            return redirect()->route('install.siteInfo');
        }

        // Get database type from environment
        $dbType = env('DB_CONNECTION', 'pgsql');
        $sqlFileName = $dbType === 'pgsql' ? 'data_pgsql.sql' : 'data_mysql.sql';
        $sqlPath = database_path($sqlFileName);

        if (!file_exists($sqlPath)) {
            return back()->withErrors(['error' => "SQL file is missing: {$sqlFileName}"]);
        }

        return response()->download($sqlPath);
    }


    public function skip()
    {
        if (getInstallState('INSTALL_DATABASE_IMPORT') == '1') {
            return redirect()->route('install.siteInfo');
        }

        try {
            getSetting('site_name');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['skip' => __('You need to import the database first')]);
        }

        setInstallState('INSTALL_DATABASE_IMPORT', '1');

        return redirect()->route('install.siteInfo');
    }


    public function siteInfo()
    {
        if (getInstallState('INSTALL_SITE_INFO') == '1') {
            return redirect()->route('install.complete');
        }

        $existingAdmin = Admin::first();

        return view('install.site_info', [
            'currentStep' => 6,
            'existingAdmin' => $existingAdmin
        ]);
    }


    public function siteInfoPost(Request $request)
    {
        set_time_limit(120);
        ini_set('max_execution_time', 120);

        // استعادة بيانات الاتصال بقاعدة البيانات من install_state.json
        $installState = installState();
        $dbConnection = $installState->get('DB_CONNECTION', env('DB_CONNECTION', 'pgsql'));
        $dbHost = $installState->get('DB_HOST');
        $dbPort = $installState->get('DB_PORT');
        $dbName = $installState->get('DB_DATABASE');
        $dbUsername = $installState->get('DB_USERNAME');
        $dbPassword = $installState->get('DB_PASSWORD');

        // إذا كانت البيانات موجودة في install_state، استخدمها لإعادة الاتصال
        if ($dbHost && $dbName && $dbUsername) {
            config([
                'database.connections.pgsql.host' => $dbHost,
                'database.connections.pgsql.port' => $dbPort ?: 5432,
                'database.connections.pgsql.database' => $dbName,
                'database.connections.pgsql.username' => $dbUsername,
                'database.connections.pgsql.password' => $dbPassword,
                'database.connections.mysql.host' => $dbHost,
                'database.connections.mysql.port' => $dbPort ?: 3306,
                'database.connections.mysql.database' => $dbName,
                'database.connections.mysql.username' => $dbUsername,
                'database.connections.mysql.password' => $dbPassword,
            ]);

            // إعادة الاتصال بقاعدة البيانات
            DB::purge($dbConnection);
            DB::reconnect($dbConnection);
        }
        
        // إعادة تعيين sequences في PostgreSQL قبل إدراج البيانات لتجنب تعارض المفاتيح
        if ($dbConnection === 'pgsql') {
            try {
                $this->resetPostgresSequences();
            } catch (Exception $e) {
                // في حالة فشل إعادة التعيين، نستمر
            }
        }

        $existingAdmin = Admin::first();
        
        $validationRules = [
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
            'admin_path' => 'required|string|alpha_dash',
        ];

        if ($existingAdmin) {
            $validationRules['admin_email'] = 'required|email|max:255|unique:admins,email,' . $existingAdmin->id;
        } else {
            $validationRules['admin_email'] = 'required|email|max:255|unique:admins,email';
        }

        $request->validate($validationRules);

        $settings = [
            ['key' => 'site_name', 'value' => $request->site_name],
            ['key' => 'site_url', 'value' => $request->site_url],
            ['key' => 'admin_path', 'value' => $request->admin_path],
            ['key' => 'api_key', 'value' => Str::random(30)],
            ['key' => 'cronjob_key', 'value' => Str::random(30)],
        ];
        
        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        if ($existingAdmin) {
            $existingAdmin->update([
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
            ]);
        } else {
            Admin::create([
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'avatar' => config('lobage.default_avatar', 'assets/img/default-user.png'),
            ]);
        }

        setInstallState('INSTALL_SITE_INFO', '1');

        return redirect()->route('install.complete');
    }

    public function complete()
    {
        if (getInstallState('SYSTEM_INSTALLED') == '1') {
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

        $siteUrl = \App\Models\Setting::where('key', 'site_url')->value('value');
        $siteName = \App\Models\Setting::where('key', 'site_name')->value('value');
        $adminPath = \App\Models\Setting::where('key', 'admin_path')->value('value');

        updateEnvFile('APP_URL', $siteUrl ?? url('/'));
        updateEnvFile('APP_NAME', $siteName ?? 'Trash Mails');
        updateEnvFile('ADMIN_PATH', $adminPath ?? 'admin');
        updateEnvFile('APP_DEBUG', 'false');
        updateEnvFile('APP_ENV', 'production');
        updateEnvFile('MAINTENANCE_MODE', 'false');

        setInstallState('SYSTEM_INSTALLED', '1');

        return view('install.complete', ['currentStep' => 7]);
    }

    /**
     * التحقق من وجود الجداول المهمة وإنشائها إذا كانت مفقودة
     * هذا يحل مشكلة عدم اكتمال استيراد SQL
     */
    protected function ensureCriticalTablesExist($dbType = 'pgsql')
    {
        $criticalTables = [
            'sessions' => [
                'pgsql' => "CREATE TABLE IF NOT EXISTS \"sessions\" (
                    \"id\" VARCHAR(255) NOT NULL PRIMARY KEY,
                    \"user_id\" BIGINT DEFAULT NULL,
                    \"ip_address\" VARCHAR(45) DEFAULT NULL,
                    \"user_agent\" TEXT,
                    \"payload\" TEXT NOT NULL,
                    \"last_activity\" INTEGER NOT NULL
                )",
                'mysql' => "CREATE TABLE IF NOT EXISTS `sessions` (
                    `id` VARCHAR(255) NOT NULL PRIMARY KEY,
                    `user_id` BIGINT DEFAULT NULL,
                    `ip_address` VARCHAR(45) DEFAULT NULL,
                    `user_agent` TEXT,
                    `payload` TEXT NOT NULL,
                    `last_activity` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            ],
            'cache' => [
                'pgsql' => "CREATE TABLE IF NOT EXISTS \"cache\" (
                    \"key\" VARCHAR(255) NOT NULL PRIMARY KEY,
                    \"value\" TEXT NOT NULL,
                    \"expiration\" INTEGER NOT NULL
                )",
                'mysql' => "CREATE TABLE IF NOT EXISTS `cache` (
                    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
                    `value` TEXT NOT NULL,
                    `expiration` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            ]
        ];
        
        try {
            foreach ($criticalTables as $tableName => $createStatements) {
                // التحقق من وجود الجدول
                $exists = false;
                
                try {
                    if ($dbType === 'pgsql') {
                        $result = DB::select("SELECT EXISTS (
                            SELECT FROM information_schema.tables 
                            WHERE table_schema = 'public' 
                            AND table_name = ?
                        ) as exists", [$tableName]);
                        $exists = $result[0]->exists ?? false;
                    } else {
                        $result = DB::select("SELECT COUNT(*) as count FROM information_schema.tables 
                            WHERE table_schema = DATABASE() 
                            AND table_name = ?", [$tableName]);
                        $exists = ($result[0]->count ?? 0) > 0;
                    }
                } catch (Exception $e) {
                    $exists = false;
                }
                
                // إنشاء الجدول إذا كان مفقوداً
                if (!$exists && isset($createStatements[$dbType])) {
                    try {
                        DB::statement($createStatements[$dbType]);
                        \Log::info("تم إنشاء الجدول المفقود: {$tableName}");
                    } catch (Exception $e) {
                        \Log::warning("فشل إنشاء الجدول {$tableName}: " . $e->getMessage());
                    }
                }
            }
            
            return true;
        } catch (Exception $e) {
            \Log::warning('فشل التحقق من الجداول المهمة: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * إعادة تعيين جميع sequences في PostgreSQL بعد استيراد البيانات
     * هذا يحل مشكلة تعارف المفاتيح الأساسية عند إدراج بيانات جديدة
     */
    protected function resetPostgresSequences()
    {
        try {
            // الحصول على جميع sequences في قاعدة البيانات
            $sequences = DB::select("
                SELECT 
                    sequence_name,
                    REPLACE(sequence_name, '_id_seq', '') as table_name
                FROM information_schema.sequences 
                WHERE sequence_schema = 'public' 
                AND sequence_name LIKE '%_id_seq'
            ");

            foreach ($sequences as $sequence) {
                $tableName = $sequence->table_name;
                $sequenceName = $sequence->sequence_name;
                
                // التحقق من وجود الجدول
                $tableExists = DB::select("
                    SELECT EXISTS (
                        SELECT FROM information_schema.tables 
                        WHERE table_schema = 'public' 
                        AND table_name = ?
                    ) as exists
                ", [$tableName])[0]->exists;
                
                if ($tableExists) {
                    // الحصول على أقصى ID في الجدول وإعادة تعيين الـ sequence
                    DB::statement("
                        SELECT setval(
                            '{$sequenceName}', 
                            COALESCE((SELECT MAX(id) FROM \"{$tableName}\"), 1),
                            true
                        )
                    ");
                }
            }
            
            return true;
        } catch (Exception $e) {
            // في حالة فشل إعادة التعيين، نسجل الخطأ ولكن نستمر
            \Log::warning('فشل إعادة تعيين sequences: ' . $e->getMessage());
            return false;
        }
    }
}
