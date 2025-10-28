#!/usr/bin/env php
<?php

/**
 * Improved PostgreSQL Connection Test
 * اختبار محسّن للاتصال بـ PostgreSQL
 */

echo "═══════════════════════════════════════════════════════════\n";
echo "  PostgreSQL Connection Test\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// قراءة ملف .env بشكل أفضل
function loadEnv($path) {
    if (!file_exists($path)) {
        die("خطأ: ملف .env غير موجود\n");
    }
    
    $env = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // تخطي التعليقات
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // تحليل السطر
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // إزالة علامات الاقتباس
            $value = trim($value, '"\'');
            
            $env[$key] = $value;
        }
    }
    
    return $env;
}

$env = loadEnv(__DIR__ . '/../.env');

// الحصول على إعدادات الاتصال
$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? '5432';
$database = $env['DB_DATABASE'] ?? 'postgres';
$username = $env['DB_USERNAME'] ?? 'postgres';
$password = $env['DB_PASSWORD'] ?? '';

// إذا كان المنفذ يحتوي على متغير، استخدم القيمة الافتراضية
if (strpos($port, '$') !== false) {
    $port = '5432';
}

echo "إعدادات الاتصال من ملف .env:\n";
echo "  Host: {$host}\n";
echo "  Port: {$port}\n";
echo "  Database: {$database}\n";
echo "  Username: {$username}\n";
echo "  Password: " . (empty($password) ? '[فارغ]' : '[محمي]') . "\n\n";

echo "محاولة الاتصال...\n";

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    
    echo "✓ تم الاتصال بنجاح!\n\n";
    
    // معلومات النسخة
    $version = $pdo->query('SELECT version()')->fetchColumn();
    echo "PostgreSQL Version:\n";
    echo "  " . substr($version, 0, 100) . "...\n\n";
    
    // قائمة الجداول
    echo "الجداول الموجودة في قاعدة البيانات:\n";
    $tables = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
        ORDER BY table_name
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "  ⚠ لا توجد جداول (قاعدة البيانات فارغة)\n\n";
        echo "  → يمكنك الآن استيراد البيانات أو تشغيل migrations\n\n";
    } else {
        $count = 0;
        foreach ($tables as $table) {
            echo "  • {$table}\n";
            $count++;
            if ($count >= 10) {
                echo "  ... و" . (count($tables) - 10) . " جدول آخر\n";
                break;
            }
        }
        echo "\n  الإجمالي: " . count($tables) . " جدول\n\n";
        
        // إحصائيات الصفوف
        echo "عدد الصفوف في الجداول الرئيسية:\n";
        $mainTables = array_slice($tables, 0, 5);
        foreach ($mainTables as $table) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM \"{$table}\"")->fetchColumn();
                echo "  {$table}: " . number_format($count) . " صف\n";
            } catch (Exception $e) {
                echo "  {$table}: [غير متاح]\n";
            }
        }
    }
    
    // حجم قاعدة البيانات
    echo "\nحجم قاعدة البيانات:\n";
    $size = $pdo->query("
        SELECT pg_size_pretty(pg_database_size(current_database()))
    ")->fetchColumn();
    echo "  {$size}\n";
    
    echo "\n═══════════════════════════════════════════════════════════\n";
    echo "  ✓ الاختبار اكتمل بنجاح\n";
    echo "  ✓ PostgreSQL جاهز للاستخدام\n";
    echo "═══════════════════════════════════════════════════════════\n";
    
} catch (PDOException $e) {
    echo "\n✗ فشل الاتصال!\n\n";
    echo "رسالة الخطأ:\n";
    echo "  " . $e->getMessage() . "\n\n";
    
    echo "الأخطاء الشائعة والحلول:\n\n";
    
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "  1. PostgreSQL غير قيد التشغيل\n";
        echo "     الحل: تأكد من أن PostgreSQL يعمل على الخادم\n\n";
    }
    
    if (strpos($e->getMessage(), 'authentication failed') !== false) {
        echo "  2. خطأ في اسم المستخدم أو كلمة المرور\n";
        echo "     الحل: تحقق من إعدادات الاتصال في ملف .env\n\n";
    }
    
    if (strpos($e->getMessage(), 'database') !== false && strpos($e->getMessage(), 'does not exist') !== false) {
        echo "  3. قاعدة البيانات غير موجودة\n";
        echo "     الحل: أنشئ قاعدة البيانات أولاً:\n";
        echo "     createdb -h {$host} -U {$username} {$database}\n\n";
    }
    
    echo "  4. تحقق من:\n";
    echo "     • أن المنفذ {$port} مفتوح\n";
    echo "     • أن جدار الحماية يسمح بالاتصال\n";
    echo "     • صلاحيات المستخدم في PostgreSQL\n\n";
    
    echo "═══════════════════════════════════════════════════════════\n";
    
    exit(1);
}
