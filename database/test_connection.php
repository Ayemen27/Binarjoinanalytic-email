#!/usr/bin/env php
<?php

/**
 * PostgreSQL Connection Test Script
 * سكريبت اختبار الاتصال بقاعدة بيانات PostgreSQL
 */

echo "═══════════════════════════════════════════════════════════\n";
echo "  PostgreSQL Connection Test\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// قراءة إعدادات الاتصال من .env
$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    die("خطأ: ملف .env غير موجود\n");
}

$env = parse_ini_file($envFile);

$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? '5432';
$database = $env['DB_DATABASE'] ?? 'postgres';
$username = $env['DB_USERNAME'] ?? 'postgres';
$password = $env['DB_PASSWORD'] ?? '';

echo "إعدادات الاتصال:\n";
echo "  Host: {$host}\n";
echo "  Port: {$port}\n";
echo "  Database: {$database}\n";
echo "  Username: {$username}\n";
echo "  Password: " . (empty($password) ? '[فارغ]' : '[*****]') . "\n\n";

echo "محاولة الاتصال...\n";

try {
    // محاولة الاتصال
    $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✓ تم الاتصال بنجاح!\n\n";
    
    // الحصول على معلومات النسخة
    $version = $pdo->query('SELECT version()')->fetchColumn();
    echo "إصدار PostgreSQL:\n";
    echo "  {$version}\n\n";
    
    // الحصول على قائمة الجداول
    echo "الجداول الموجودة:\n";
    $tables = $pdo->query("
        SELECT tablename 
        FROM pg_tables 
        WHERE schemaname = 'public'
        ORDER BY tablename
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "  [لا توجد جداول]\n\n";
    } else {
        foreach ($tables as $i => $table) {
            echo "  " . ($i + 1) . ". {$table}\n";
        }
        echo "\n";
        echo "  إجمالي: " . count($tables) . " جدول\n\n";
    }
    
    // احصائيات قاعدة البيانات
    echo "احصائيات قاعدة البيانات:\n";
    
    $stats = $pdo->query("
        SELECT 
            pg_database.datname,
            pg_size_pretty(pg_database_size(pg_database.datname)) AS size
        FROM pg_database
        WHERE datname = '{$database}'
    ")->fetch();
    
    if ($stats) {
        echo "  الحجم: {$stats['size']}\n";
    }
    
    // عدد الصفوف لكل جدول
    if (!empty($tables)) {
        echo "\nعدد الصفوف في كل جدول:\n";
        foreach ($tables as $table) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM \"{$table}\"")->fetchColumn();
                echo "  {$table}: " . number_format($count) . " صف\n";
            } catch (Exception $e) {
                echo "  {$table}: [خطأ في القراءة]\n";
            }
        }
    }
    
    echo "\n═══════════════════════════════════════════════════════════\n";
    echo "  ✓ الاختبار اكتمل بنجاح\n";
    echo "═══════════════════════════════════════════════════════════\n";
    
} catch (PDOException $e) {
    echo "\n✗ فشل الاتصال!\n\n";
    echo "رسالة الخطأ:\n";
    echo "  " . $e->getMessage() . "\n\n";
    
    echo "الحلول المقترحة:\n";
    echo "  1. تحقق من أن PostgreSQL قيد التشغيل\n";
    echo "  2. تحقق من صحة معلومات الاتصال في ملف .env\n";
    echo "  3. تحقق من أن المنفذ {$port} مفتوح\n";
    echo "  4. تحقق من صلاحيات المستخدم\n\n";
    
    echo "═══════════════════════════════════════════════════════════\n";
    
    exit(1);
}
