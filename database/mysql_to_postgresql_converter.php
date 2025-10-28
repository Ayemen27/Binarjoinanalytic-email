#!/usr/bin/env php
<?php

/**
 * MySQL to PostgreSQL Advanced Converter
 * تحويل متقدم من MySQL إلى PostgreSQL
 */

class AdvancedMySQLToPostgreSQLConverter
{
    private $content;
    private $tables = [];
    private $insertStatements = [];
    
    public function __construct($content)
    {
        $this->content = $content;
    }
    
    public function convert()
    {
        echo "═══════════════════════════════════════════════════════════\n";
        echo "  MySQL to PostgreSQL Converter\n";
        echo "═══════════════════════════════════════════════════════════\n\n";
        
        $steps = [
            'إزالة أوامر MySQL الخاصة' => 'removeMySQLCommands',
            'تحويل CREATE TABLE' => 'convertCreateTable',
            'تحويل أنواع البيانات' => 'convertDataTypes',
            'تحويل AUTO_INCREMENT' => 'convertAutoIncrement',
            'تحويل الفهارس والمفاتيح' => 'convertIndexesAndKeys',
            'إزالة ENGINE و CHARSET' => 'removeEngineAndCharset',
            'تحويل INSERT statements' => 'convertInserts',
            'تحويل FOREIGN KEYS' => 'convertForeignKeys',
            'إضافة رأس PostgreSQL' => 'addPostgreSQLHeader',
        ];
        
        foreach ($steps as $description => $method) {
            echo "→ {$description}... ";
            $this->$method();
            echo "✓\n";
        }
        
        echo "\n✓ اكتمل التحويل بنجاح!\n";
        
        return $this->content;
    }
    
    private function removeMySQLCommands()
    {
        // إزالة التعليقات الخاصة بـ MySQL
        $this->content = preg_replace('/\/\*![\d\s\w=@_;,]+\*\/;?\s*/s', '', $this->content);
        
        // إزالة أوامر SET
        $this->content = preg_replace('/SET\s+(SQL_MODE|@OLD_.*?|NAMES|time_zone)\s*=.*?;/i', '', $this->content);
        
        // إزالة START TRANSACTION و BEGIN و COMMIT لأننا سنضيف BEGIN/COMMIT واحد في الرأس والتذييل
        $this->content = str_replace('START TRANSACTION;', '', $this->content);
        $this->content = preg_replace('/^\s*BEGIN;\s*$/m', '', $this->content);
        $this->content = preg_replace('/^\s*COMMIT;\s*$/m', '', $this->content);
        
        // إزالة الأسطر الفارغة المتعددة
        $this->content = preg_replace('/\n{3,}/', "\n\n", $this->content);
    }
    
    private function convertCreateTable()
    {
        // إزالة backticks واستبدالها بـ double quotes
        $this->content = str_replace('`', '"', $this->content);
    }
    
    private function convertDataTypes()
    {
        $typeMapping = [
            // unsigned integers
            '/\bbigint\s+UNSIGNED\b/i' => 'BIGINT',
            '/\bint\s+UNSIGNED\b/i' => 'INTEGER',
            '/\bmediumint\s+UNSIGNED\b/i' => 'INTEGER',
            '/\bsmallint\s+UNSIGNED\b/i' => 'INTEGER',
            '/\btinyint\s+UNSIGNED\b/i' => 'SMALLINT',
            
            // tinyint(1) للـ boolean
            '/\btinyint\s*\(1\)/i' => 'BOOLEAN',
            '/\btinyint\b/i' => 'SMALLINT',
            
            // text types
            '/\blongtext\b/i' => 'TEXT',
            '/\bmediumtext\b/i' => 'TEXT',
            '/\btinytext\b/i' => 'TEXT',
            
            // datetime
            '/\bdatetime\b/i' => 'TIMESTAMP',
            
            // إزالة CHARACTER SET و COLLATE من varchar
            '/varchar\s*\((\d+)\)\s+CHARACTER\s+SET\s+\w+\s+COLLATE\s+[\w_]+/i' => 'VARCHAR($1)',
            '/varchar\s*\((\d+)\)\s+CHARACTER\s+SET\s+\w+/i' => 'VARCHAR($1)',
            '/varchar\s*\((\d+)\)\s+COLLATE\s+[\w_]+/i' => 'VARCHAR($1)',
        ];
        
        foreach ($typeMapping as $pattern => $replacement) {
            $this->content = preg_replace($pattern, $replacement, $this->content);
        }
    }
    
    private function convertAutoIncrement()
    {
        // تحويل bigint NOT NULL AUTO_INCREMENT إلى BIGSERIAL
        $this->content = preg_replace(
            '/"(\w+)"\s+BIGINT\s+NOT\s+NULL\s*,\s*$/m',
            '"$1" BIGSERIAL NOT NULL,',
            $this->content
        );
        
        // إزالة AUTO_INCREMENT من ALTER TABLE
        $this->content = preg_replace(
            '/MODIFY\s+"(\w+)"\s+BIGINT\s+NOT\s+NULL\s+AUTO_INCREMENT;/i',
            '',
            $this->content
        );
        
        // إزالة سطور AUTO_INCREMENT المنفصلة
        $this->content = preg_replace('/--\s*AUTO_INCREMENT.*?\n/i', '', $this->content);
    }
    
    private function convertIndexesAndKeys()
    {
        // تحويل ADD PRIMARY KEY
        $this->content = preg_replace(
            '/ALTER\s+TABLE\s+"(\w+)"\s+ADD\s+PRIMARY\s+KEY\s+\("(\w+)"\);/i',
            'ALTER TABLE "$1" ADD CONSTRAINT "$1_pkey" PRIMARY KEY ("$2");',
            $this->content
        );
        
        // تحويل ADD UNIQUE KEY
        $this->content = preg_replace(
            '/ADD\s+UNIQUE\s+KEY\s+"(\w+)"\s+\("(\w+)"\)/i',
            'ADD CONSTRAINT "$1" UNIQUE ("$2")',
            $this->content
        );
        
        // تحويل ADD KEY (index)
        $this->content = preg_replace(
            '/ADD\s+KEY\s+"(\w+)"\s+\("(\w+)"\)/i',
            'CREATE INDEX "$1" ON "$table_name" ("$2")',
            $this->content
        );
    }
    
    private function removeEngineAndCharset()
    {
        // إزالة ENGINE=InnoDB
        $this->content = preg_replace('/\)\s*ENGINE\s*=\s*\w+(\s+DEFAULT\s+CHARSET\s*=\s*\w+)?(\s+COLLATE\s*=?\s*[\w_]+)?;/i', ');', $this->content);
        
        // إزالة DEFAULT CHARSET
        $this->content = preg_replace('/DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $this->content);
        
        // إزالة CHARACTER SET من definitions
        $this->content = preg_replace('/CHARACTER\s+SET\s+\w+/i', '', $this->content);
        
        // إزالة COLLATE
        $this->content = preg_replace('/COLLATE\s+[\w_]+/i', '', $this->content);
    }
    
    private function convertInserts()
    {
        // في PostgreSQL، نحتاج للتعامل مع القيم الفارغة بشكل مختلف
        // لكن بشكل عام، INSERT statements متوافقة
        
        // فقط نتأكد من عدم وجود backticks (تم معالجتها سابقاً)
    }
    
    private function convertForeignKeys()
    {
        // تحويل FOREIGN KEY constraints
        // Laravel migrations ستتعامل معها، لكن إذا كانت في SQL:
        
        $this->content = preg_replace(
            '/CONSTRAINT\s+"(\w+)"\s+FOREIGN\s+KEY\s+\("(\w+)"\)\s+REFERENCES\s+"(\w+)"\s+\("(\w+)"\)(\s+ON\s+DELETE\s+\w+)?(\s+ON\s+UPDATE\s+\w+)?/i',
            'CONSTRAINT "$1" FOREIGN KEY ("$2") REFERENCES "$3" ("$4")$5$6',
            $this->content
        );
    }
    
    private function addPostgreSQLHeader()
    {
        $header = "-- ═══════════════════════════════════════════════════════════\n";
        $header .= "-- PostgreSQL Database Export\n";
        $header .= "-- Converted from MySQL dump\n";
        $header .= "-- Conversion Date: " . date('Y-m-d H:i:s') . "\n";
        $header .= "-- ═══════════════════════════════════════════════════════════\n\n";
        $header .= "-- تعطيل المحفزات والقيود أثناء الاستيراد\n";
        $header .= "SET session_replication_role = 'replica';\n";
        $header .= "SET client_encoding = 'UTF8';\n";
        $header .= "SET standard_conforming_strings = on;\n\n";
        $header .= "BEGIN;\n\n";
        
        $this->content = $header . $this->content;
        
        // إضافة تذييل
        $footer = "\n\n-- إعادة تفعيل المحفزات والقيود\n";
        $footer .= "SET session_replication_role = 'origin';\n\n";
        $footer .= "COMMIT;\n";
        $footer .= "\n-- ═══════════════════════════════════════════════════════════\n";
        $footer .= "-- انتهى الاستيراد\n";
        $footer .= "-- ═══════════════════════════════════════════════════════════\n";
        
        $this->content .= $footer;
    }
    
    public function getContent()
    {
        return $this->content;
    }
}

// ════════════════════════════════════════════════════════════
// الاستخدام
// ════════════════════════════════════════════════════════════

if (php_sapi_name() === 'cli') {
    // قراءة من ملف أو من stdin
    if ($argc > 1) {
        $inputFile = $argv[1];
        $outputFile = $argv[2] ?? str_replace('.sql', '_postgres.sql', $inputFile);
        
        if (!file_exists($inputFile)) {
            die("الملف غير موجود: {$inputFile}\n");
        }
        
        $content = file_get_contents($inputFile);
    } else {
        echo "الاستخدام: php mysql_to_postgresql_converter.php input.sql [output.sql]\n";
        echo "أو قم بنسخ محتوى SQL ولصقه أدناه (اضغط Ctrl+D عند الانتهاء):\n\n";
        $content = stream_get_contents(STDIN);
    }
    
    $converter = new AdvancedMySQLToPostgreSQLConverter($content);
    $result = $converter->convert();
    
    if (isset($outputFile)) {
        file_put_contents($outputFile, $result);
        echo "تم حفظ الملف المحول في: {$outputFile}\n";
    } else {
        echo "\n" . str_repeat('═', 60) . "\n";
        echo $result;
    }
}
