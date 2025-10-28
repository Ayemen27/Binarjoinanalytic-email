<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class InstallService
{
    public function checkRequirements()
    {

        // Logic to check server requirements
        return [

            'php_version' => version_compare(PHP_VERSION, '8.2', '>='),
            //'open_basedir' => ini_get('open_basedir') === '',  // Check if open_basedir is none
            'extensions' => [
                'Mbstring' => extension_loaded('mbstring'),
                'BCMath' => extension_loaded('bcmath'),
                'Ctype' => extension_loaded('ctype'),
                'Json' => extension_loaded('json'),
                'OpenSSL' => extension_loaded('openssl'),
                'PDO' => extension_loaded('pdo'),
                'Tokenizer' => extension_loaded('tokenizer'),
                'XML' => extension_loaded('xml'),
                'Fileinfo' => extension_loaded('fileinfo'),
                'Fopen' => ini_get('allow_url_fopen'),
                'IMAP' => extension_loaded('imap'),
                'Iconv' => extension_loaded('iconv'),
                'Zip' => extension_loaded('zip'),
                'cURL' => extension_loaded('curl'),
                'GD' => extension_loaded('gd'),
                // Add more requirements as needed

            ],
        ];
    }



    public function checkFilePermissions()
    {
        // Logic to check file permissions
        return [
            'storage' => is_writable(base_path('storage/')),
            'bootstrap/' => is_writable(base_path('bootstrap/')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            'public/' => is_writable(base_path('public/')),
            // Add more file permission checks as needed
        ];
    }

    public function checkLicense($purchaseCode, $debugMode = false)
    {
        // محاكاة نظام الترخيص - تجاوز التحقق في وضع التطوير
        if (env('APP_ENV') === 'local' || env('APP_DEBUG') === 'true' || env('APP_DEBUG') === true) {
            return [
                'status' => true,
                'message' => 'وضع التطوير - تم تجاوز التحقق من الترخيص',
                'data' => [
                    'purchase_code' => $purchaseCode,
                    'license_type' => 'development',
                    'client' => 'development_client',
                    'verified_at' => now()->toDateTimeString(),
                    'expires_at' => now()->addYears(10)->toDateTimeString(),
                    'support_until' => now()->addYears(10)->toDateTimeString()
                ]
            ];
        }

        try {
            // نقطة نهاية API الفعلية للتحقق من الترخيص
            $apiUrl = 'https://your-license-server.com/api/verify';

            $response = Http::timeout(10)->post($apiUrl, [
                'purchase_code' => $purchaseCode,
                'domain' => request()->getHost(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === true) {
                    return [
                        'status' => true,
                        'message' => 'تم التحقق من الترخيص بنجاح',
                        'data' => $data['data'] ?? []
                    ];
                }
            }

            return [
                'status' => false,
                'message' => 'فشل التحقق من الترخيص',
                'action' => false
            ];
        } catch (\Exception $e) {
            if ($debugMode) {
                return [
                    'status' => false,
                    'message' => 'خطأ: ' . $e->getMessage(),
                    'action' => false
                ];
            }

            return [
                'status' => false,
                'message' => 'تعذر التحقق من الترخيص. يرجى التحقق من اتصالك بالإنترنت.',
                'action' => false
            ];
        }
    }


    /**
     * إرجاع استجابة محاكاة للترخيص - للتطوير فقط
     */
    private function getMockLicenseResponse($key)
    {
        // تحقق من أن المفتاح ليس فارغاً
        if (empty($key)) {
            return [
                'status' => false,
                'message' => 'رمز الشراء مطلوب',
                'action' => false,
            ];
        }

        // استجابة محاكاة ناجحة
        return [
            'status' => true,
            'message' => 'تم التحقق من الترخيص بنجاح (وضع المحاكاة)',
            'action' => false,
            'data' => [
                'item_id' => '48095748',
                'item_name' => 'Trash Mails - Ultimate Temporary Email Address System',
                'buyer' => 'Mock Development User',
                'purchase_code' => $key,
                'license' => 'Regular License (Mock)',
                'supported_until' => now()->addYears(10)->format('Y-m-d H:i:s'),
                'purchase_count' => 1,
            ],
        ];
    }

    public function importDatabase()
    {
        return true;
        // Logic to import database
        //DB::connection()->getPdo()->exec(file_get_contents(database_path('data.sql')));
    }
}