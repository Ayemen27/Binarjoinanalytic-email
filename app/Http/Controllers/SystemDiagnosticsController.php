<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;

class SystemDiagnosticsController extends Controller
{
    public function index()
    {
        $diagnostics = [
            'system_installed' => false,
            'database_connected' => false,
            'install_state_exists' => false,
            'install_state_valid' => false,
            'database_config' => [],
            'errors' => [],
            'recommendations' => [],
        ];

        try {
            // Check if install_state.json exists
            $installStatePath = storage_path('app/install_state.json');
            $diagnostics['install_state_exists'] = file_exists($installStatePath);

            if ($diagnostics['install_state_exists']) {
                $installState = json_decode(file_get_contents($installStatePath), true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    $diagnostics['install_state_valid'] = true;
                    $diagnostics['system_installed'] = isset($installState['SYSTEM_INSTALLED']) && $installState['SYSTEM_INSTALLED'] === '1';
                    
                    // Show current database configuration (without password)
                    $diagnostics['database_config'] = [
                        'connection' => $installState['DB_CONNECTION'] ?? 'N/A',
                        'host' => $installState['DB_HOST'] ?? 'N/A',
                        'port' => $installState['DB_PORT'] ?? 'N/A',
                        'database' => $installState['DB_DATABASE'] ?? 'N/A',
                        'username' => $installState['DB_USERNAME'] ?? 'N/A',
                        'password_set' => !empty($installState['DB_PASSWORD']),
                    ];
                } else {
                    $diagnostics['errors'][] = 'ملف install_state.json تالف (JSON غير صالح)';
                    $diagnostics['recommendations'][] = 'يجب إعادة تثبيت النظام';
                }
            } else {
                $diagnostics['errors'][] = 'ملف install_state.json غير موجود';
                $diagnostics['recommendations'][] = 'يجب تثبيت النظام أولاً';
            }

            // Test database connection
            try {
                DB::connection()->getPdo();
                $diagnostics['database_connected'] = true;
                $diagnostics['recommendations'][] = '✓ الاتصال بقاعدة البيانات ناجح';
            } catch (Exception $e) {
                $diagnostics['database_connected'] = false;
                $diagnostics['errors'][] = 'فشل الاتصال بقاعدة البيانات: ' . $e->getMessage();
                $diagnostics['recommendations'][] = 'تحقق من بيانات الاتصال في ملف install_state.json';
                $diagnostics['recommendations'][] = 'تأكد من أن قاعدة البيانات الخارجية متاحة';
            }

        } catch (Exception $e) {
            $diagnostics['errors'][] = 'خطأ عام: ' . $e->getMessage();
        }

        return view('diagnostics', compact('diagnostics'));
    }

    public function testConnection()
    {
        try {
            DB::connection()->getPdo();
            return response()->json([
                'success' => true,
                'message' => 'الاتصال بقاعدة البيانات ناجح!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال: ' . $e->getMessage()
            ], 500);
        }
    }
}
