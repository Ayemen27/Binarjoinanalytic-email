<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class InstallStateRepository
{
    /**
     * مسار ملف حالة التثبيت
     */
    protected $filePath;

    /**
     * البيانات المخزنة في الذاكرة
     */
    protected $data = null;

    public function __construct()
    {
        // استخدام storage/app لأنه محمي ومضمون الوصول إليه
        $this->filePath = storage_path('app/install_state.json');
        
        // إذا لم يكن الملف موجوداً، إنشاؤه مع القيم الافتراضية
        if (!File::exists($this->filePath)) {
            $this->initializeFromEnv();
        }
    }

    /**
     * قراءة حالة التثبيت من الملف
     */
    protected function load()
    {
        if ($this->data !== null) {
            return $this->data;
        }

        if (!File::exists($this->filePath)) {
            $this->initializeFromEnv();
        }

        try {
            $contents = File::get($this->filePath);
            $this->data = json_decode($contents, true) ?? $this->getDefaultState();
        } catch (\Exception $e) {
            $this->data = $this->getDefaultState();
        }

        return $this->data;
    }

    /**
     * حفظ حالة التثبيت إلى الملف
     */
    protected function save()
    {
        try {
            // التأكد من وجود مجلد storage/app
            if (!File::isDirectory(storage_path('app'))) {
                File::makeDirectory(storage_path('app'), 0755, true);
            }

            $jsonData = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            File::put($this->filePath, $jsonData);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('فشل حفظ حالة التثبيت: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * القيم الافتراضية لحالة التثبيت
     */
    protected function getDefaultState()
    {
        return [
            'INSTALL_WELCOME' => '0',
            'INSTALL_REQUIREMENTS' => '0',
            'INSTALL_FILE_PERMISSIONS' => '0',
            'INSTALL_LICENSE' => '0',
            'INSTALL_DATABASE_INFO' => '0',
            'INSTALL_DATABASE_IMPORT' => '0',
            'INSTALL_SITE_INFO' => '0',
            'SYSTEM_INSTALLED' => '0',
            'installed_at' => null,
            'last_updated' => null,
        ];
    }

    /**
     * تهيئة الملف من قيم .env (للترحيل من النظام القديم)
     */
    protected function initializeFromEnv()
    {
        $state = [
            'INSTALL_WELCOME' => env('INSTALL_WELCOME', '0'),
            'INSTALL_REQUIREMENTS' => env('INSTALL_REQUIREMENTS', '0'),
            'INSTALL_FILE_PERMISSIONS' => env('INSTALL_FILE_PERMISSIONS', '0'),
            'INSTALL_LICENSE' => env('INSTALL_LICENSE', '0'),
            'INSTALL_DATABASE_INFO' => env('INSTALL_DATABASE_INFO', '0'),
            'INSTALL_DATABASE_IMPORT' => env('INSTALL_DATABASE_IMPORT', '0'),
            'INSTALL_SITE_INFO' => env('INSTALL_SITE_INFO', '0'),
            'SYSTEM_INSTALLED' => env('SYSTEM_INSTALLED', '0'),
            'installed_at' => env('SYSTEM_INSTALLED') == '1' ? now()->toDateTimeString() : null,
            'last_updated' => now()->toDateTimeString(),
        ];

        $this->data = $state;
        $this->save();
    }

    /**
     * الحصول على قيمة محددة من حالة التثبيت
     */
    public function get($key, $default = '0')
    {
        $data = $this->load();
        return $data[$key] ?? $default;
    }

    /**
     * تعيين قيمة محددة في حالة التثبيت
     */
    public function set($key, $value)
    {
        $this->load();
        $this->data[$key] = (string) $value;
        $this->data['last_updated'] = now()->toDateTimeString();
        
        // إذا تم تعيين SYSTEM_INSTALLED إلى 1، حفظ تاريخ التثبيت
        if ($key === 'SYSTEM_INSTALLED' && $value == '1' && empty($this->data['installed_at'])) {
            $this->data['installed_at'] = now()->toDateTimeString();
        }
        
        return $this->save();
    }

    /**
     * تعيين عدة قيم دفعة واحدة
     */
    public function setMultiple(array $values)
    {
        $this->load();
        
        foreach ($values as $key => $value) {
            $this->data[$key] = (string) $value;
        }
        
        $this->data['last_updated'] = now()->toDateTimeString();
        
        // إذا تم تعيين SYSTEM_INSTALLED إلى 1، حفظ تاريخ التثبيت
        if (isset($values['SYSTEM_INSTALLED']) && $values['SYSTEM_INSTALLED'] == '1' && empty($this->data['installed_at'])) {
            $this->data['installed_at'] = now()->toDateTimeString();
        }
        
        return $this->save();
    }

    /**
     * التحقق من اكتمال التثبيت
     */
    public function isInstalled()
    {
        return $this->get('SYSTEM_INSTALLED') == '1';
    }

    /**
     * التحقق من اكتمال خطوة معينة
     */
    public function isStepCompleted($step)
    {
        return $this->get($step) == '1';
    }

    /**
     * إعادة تعيين حالة التثبيت (للاختبار فقط)
     */
    public function reset()
    {
        $this->data = $this->getDefaultState();
        return $this->save();
    }

    /**
     * الحصول على جميع البيانات
     */
    public function all()
    {
        return $this->load();
    }
}
