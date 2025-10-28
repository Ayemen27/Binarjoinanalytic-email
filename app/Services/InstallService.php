<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;

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

    public function checkLicense($key, $back_up = false)
    {
        // وضع المحاكاة - للتطوير والاختبار فقط
        if (env('LICENSE_MOCK_MODE', false) === true || env('LICENSE_MOCK_MODE', false) === 'true') {
            return $this->getMockLicenseResponse($key);
        }

        try {
            if (!$back_up) {
                $client = new Client([
                    'base_uri' => config('lobage.api')
                ]);
            } else {
                $client = new Client([
                    'base_uri' => config('lobage.api_v2')
                ]);
            }

            $response = $client->post('install', [
                'query' => [
                    'purchase_code' => $key,
                    'url' => url('/'),
                    'id' => config('lobage.id'),
                ]
            ]);

            $sale = json_decode($response->getBody(), true);
            return $sale;
        } catch (RequestException $e) {
            $sale = false;

            if ($e->hasResponse()) {
                $sale = json_decode($e->getResponse()->getBody()->getContents(), true);
            }

            return $sale;
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