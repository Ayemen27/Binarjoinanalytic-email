#!/usr/bin/env php
<?php

/**
 * MySQL to PostgreSQL Advanced Converter
 * تحويل متقدم من MySQL إلى PostgreSQL - نسخة محسّنة
 */

class AdvancedMySQLToPostgreSQLConverter
{
    private $content;
    private $tables = [];
    private $currentTable = null;
    
    public function __construct($content)
    {
        $this->content = $content;
    }
    
    public function convert()
    {
        echo "═══════════════════════════════════════════════════════════\n";
        echo "  MySQL to PostgreSQL Converter - Enhanced Version\n";
        echo "═══════════════════════════════════════════════════════════\n\n";
        
        $steps = [
            'إزالة أوامر MySQL الخاصة' => 'removeMySQLCommands',
            'استخراج أسماء الجداول' => 'extractTableNames',
            'تحويل CREATE TABLE' => 'convertCreateTable',
            'تحويل أنواع البيانات' => 'convertDataTypes',
            'تحويل AUTO_INCREMENT' => 'convertAutoIncrement',
            'إزالة ENGINE و CHARSET' => 'removeEngineAndCharset',
            'تحويل الفهارس والمفاتيح' => 'convertIndexesAndKeys',
            'تحويل INSERT statements' => 'convertInserts',
            'تحويل FOREIGN KEYS' => 'convertForeignKeys',
            'تنظيف الصيغة النهائية' => 'cleanupSyntax',
            'إضافة رأس PostgreSQL' => 'addPostgreSQLHeader',
        ];
        
        foreach ($steps as $description => $method) {
            echo "→ {$description}... ";
            $this->$method();
            echo "✓\n";
        }
        
        echo "\n✓ اكتمل التحويل بنجاح!\n";
        echo "✓ تم تحويل " . count($this->tables) . " جدول\n";
        
        return $this->content;
    }
    
    private function extractTableNames()
    {
        // استخراج أسماء جميع الجداول من CREATE TABLE
        preg_match_all('/CREATE\s+TABLE\s+[`"]?(\w+)[`"]?/i', $this->content, $matches);
        if (!empty($matches[1])) {
            $this->tables = $matches[1];
        }
    }
    
    private function removeMySQLCommands()
    {
        // إزالة التعليقات الخاصة بـ MySQL
        $this->content = preg_replace('/\/\*!\d+\s+.*?\*\/;?\s*/s', '', $this->content);
        
        // إزالة أوامر SET المختلفة
        $patterns = [
            '/SET\s+SQL_MODE\s*=.*?;/is',
            '/SET\s+@OLD_.*?;/is',
            '/SET\s+NAMES\s+.*?;/is',
            '/SET\s+time_zone\s*=.*?;/is',
            '/SET\s+character_set_client\s*=.*?;/is',
        ];
        
        foreach ($patterns as $pattern) {
            $this->content = preg_replace($pattern, '', $this->content);
        }
        
        // إزالة START TRANSACTION و BEGIN و COMMIT
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
            // unsigned integers - BIGINT
            '/\bBIGINT\s+UNSIGNED\s+NOT\s+NULL\b/i' => 'BIGSERIAL NOT NULL',
            '/\bBIGINT\s+UNSIGNED\b/i' => 'BIGINT',
            
            // unsigned integers - INT
            '/\bINT\s+UNSIGNED\s+NOT\s+NULL\b/i' => 'SERIAL NOT NULL',
            '/\bINT\s+UNSIGNED\b/i' => 'INTEGER',
            '/\bINTEGER\s+UNSIGNED\b/i' => 'INTEGER',
            
            // unsigned integers - others
            '/\bMEDIUMINT\s+UNSIGNED\b/i' => 'INTEGER',
            '/\bSMALLINT\s+UNSIGNED\b/i' => 'INTEGER',
            '/\bTINYINT\s+UNSIGNED\b/i' => 'SMALLINT',
            
            // tinyint(1) للـ boolean
            '/\bTINYINT\s*\(1\)/i' => 'BOOLEAN',
            '/\bTINYINT\b/i' => 'SMALLINT',
            
            // text types
            '/\bLONGTEXT\b/i' => 'TEXT',
            '/\bMEDIUMTEXT\b/i' => 'TEXT',
            '/\bTINYTEXT\b/i' => 'TEXT',
            
            // datetime
            '/\bDATETIME\b/i' => 'TIMESTAMP',
            
            // إزالة CHARACTER SET و COLLATE من text/varchar
            '/TEXT\s+CHARACTER\s+SET\s+\w+\s+COLLATE\s+[\w_]+/i' => 'TEXT',
            '/TEXT\s+CHARACTER\s+SET\s+\w+/i' => 'TEXT',
            '/VARCHAR\s*\((\d+)\)\s+CHARACTER\s+SET\s+\w+\s+COLLATE\s+[\w_]+/i' => 'VARCHAR($1)',
            '/VARCHAR\s*\((\d+)\)\s+CHARACTER\s+SET\s+\w+/i' => 'VARCHAR($1)',
            '/VARCHAR\s*\((\d+)\)\s+COLLATE\s+[\w_]+/i' => 'VARCHAR($1)',
        ];
        
        foreach ($typeMapping as $pattern => $replacement) {
            $this->content = preg_replace($pattern, $replacement, $this->content);
        }
    }
    
    private function convertAutoIncrement()
    {
        // تحويل BIGINT NOT NULL (من CREATE TABLE) - تم معالجته في convertDataTypes
        
        // إزالة AUTO_INCREMENT من MODIFY statements
        $this->content = preg_replace(
            '/MODIFY\s+"(\w+)"\s+(BIGINT|INT|INTEGER)\s+(UNSIGNED\s+)?NOT\s+NULL\s+AUTO_INCREMENT;/i',
            '',
            $this->content
        );
        
        // إزالة سطور AUTO_INCREMENT المنفصلة
        $this->content = preg_replace('/--\s*AUTO_INCREMENT.*?\n/i', '', $this->content);
    }
    
    private function convertIndexesAndKeys()
    {
        // معالجة ALTER TABLE مع الفهارس والمفاتيح
        // نحتاج لمعالجة كل ALTER TABLE بشكل منفصل لاستخراج اسم الجدول
        
        $this->content = preg_replace_callback(
            '/ALTER\s+TABLE\s+"(\w+)"\s+(.*?);/is',
            function($matches) {
                $tableName = $matches[1];
                $alterContent = $matches[2];
                $result = [];
                
                // تقسيم الأوامر المتعددة (ADD ... , ADD ...)
                $commands = preg_split('/,\s*(?=ADD\s+)/i', $alterContent);
                
                foreach ($commands as $command) {
                    $command = trim($command);
                    
                    // ADD PRIMARY KEY
                    if (preg_match('/ADD\s+PRIMARY\s+KEY\s+\("(\w+)"\)/i', $command, $m)) {
                        $result[] = "ALTER TABLE \"{$tableName}\" ADD CONSTRAINT \"{$tableName}_pkey\" PRIMARY KEY (\"{$m[1]}\");";
                    }
                    // ADD UNIQUE KEY
                    elseif (preg_match('/ADD\s+UNIQUE\s+KEY\s+"(\w+)"\s+\((.*?)\)/i', $command, $m)) {
                        $result[] = "ALTER TABLE \"{$tableName}\" ADD CONSTRAINT \"{$m[1]}\" UNIQUE ({$m[2]});";
                    }
                    // ADD KEY (index) - single column
                    elseif (preg_match('/ADD\s+KEY\s+"(\w+)"\s+\("(\w+)"\)/i', $command, $m)) {
                        $result[] = "CREATE INDEX \"{$m[1]}\" ON \"{$tableName}\" (\"{$m[2]}\");";
                    }
                    // ADD KEY (index) - multiple columns
                    elseif (preg_match('/ADD\s+KEY\s+"(\w+)"\s+\((.*?)\)/i', $command, $m)) {
                        $result[] = "CREATE INDEX \"{$m[1]}\" ON \"{$tableName}\" ({$m[2]});";
                    }
                    // MODIFY (ignore for now)
                    elseif (preg_match('/MODIFY\s+/i', $command)) {
                        // تم معالجته في convertAutoIncrement
                    }
                    else {
                        // أوامر أخرى - نبقيها كما هي
                        if (trim($command)) {
                            $result[] = "ALTER TABLE \"{$tableName}\" {$command};";
                        }
                    }
                }
                
                return implode("\n", $result);
            },
            $this->content
        );
    }
    
    private function removeEngineAndCharset()
    {
        // إزالة ENGINE=InnoDB وما شابه
        $this->content = preg_replace(
            '/\)\s*ENGINE\s*=\s*\w+(\s+DEFAULT\s+CHARSET\s*=\s*\w+)?(\s+COLLATE\s*=?\s*[\w_]+)?(\s+ROW_FORMAT\s*=\s*\w+)?;/i',
            ');',
            $this->content
        );
        
        // إزالة DEFAULT CHARSET
        $this->content = preg_replace('/DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $this->content);
        
        // إزالة CHARACTER SET من definitions المتبقية
        $this->content = preg_replace('/CHARACTER\s+SET\s+\w+/i', '', $this->content);
        
        // إزالة COLLATE
        $this->content = preg_replace('/COLLATE\s+[\w_]+/i', '', $this->content);
    }
    
    private function convertInserts()
    {
        // في PostgreSQL، القوس المفردة يتم escape بمضاعفتها '' بدلاً من \'
        $this->content = str_replace("\\'", "''", $this->content);
        
        // تحويل MySQL escape (\") إلى PostgreSQL
        $this->content = str_replace('\\"', '"', $this->content);
        
        // تحويل \n إلى newline فعلي في النصوص
        // PostgreSQL يفهم \n بشكل مختلف
    }
    
    private function convertForeignKeys()
    {
        // معالجة FOREIGN KEY constraints مع ALTER TABLE
        $this->content = preg_replace_callback(
            '/ALTER\s+TABLE\s+"(\w+)"\s+ADD\s+CONSTRAINT\s+"(\w+)"\s+FOREIGN\s+KEY\s+\("(\w+)"\)\s+REFERENCES\s+"(\w+)"\s+\("(\w+)"\)((?:\s+ON\s+(?:DELETE|UPDATE)\s+(?:CASCADE|SET\s+NULL|RESTRICT|NO\s+ACTION))*)/is',
            function($matches) {
                $tableName = $matches[1];
                $constraintName = $matches[2];
                $column = $matches[3];
                $refTable = $matches[4];
                $refColumn = $matches[5];
                $actions = isset($matches[6]) ? $matches[6] : '';
                
                return "ALTER TABLE \"{$tableName}\" ADD CONSTRAINT \"{$constraintName}\" FOREIGN KEY (\"{$column}\") REFERENCES \"{$refTable}\" (\"{$refColumn}\"){$actions};";
            },
            $this->content
        );
        
        // معالجة FOREIGN KEY مع أعمدة متعددة
        $this->content = preg_replace_callback(
            '/ALTER\s+TABLE\s+"(\w+)"\s+ADD\s+CONSTRAINT\s+"(\w+)"\s+FOREIGN\s+KEY\s+\((.*?)\)\s+REFERENCES\s+"(\w+)"\s+\((.*?)\)((?:\s+ON\s+(?:DELETE|UPDATE)\s+(?:CASCADE|SET\s+NULL|RESTRICT|NO\s+ACTION))*)/is',
            function($matches) {
                $tableName = $matches[1];
                $constraintName = $matches[2];
                $columns = $matches[3];
                $refTable = $matches[4];
                $refColumns = $matches[5];
                $actions = isset($matches[6]) ? $matches[6] : '';
                
                return "ALTER TABLE \"{$tableName}\" ADD CONSTRAINT \"{$constraintName}\" FOREIGN KEY ({$columns}) REFERENCES \"{$refTable}\" ({$refColumns}){$actions};";
            },
            $this->content
        );
    }
    
    private function cleanupSyntax()
    {
        // إزالة الفواصل المنقوطة الزائدة
        $this->content = preg_replace('/;{2,}/', ';', $this->content);
        
        // إزالة المسافات الزائدة قبل الفاصلة المنقوطة
        $this->content = preg_replace('/\s+;/', ';', $this->content);
        
        // تنظيف الأسطر الفارغة المتعددة
        $this->content = preg_replace('/\n{3,}/', "\n\n", $this->content);
    }
    
    private function addPostgreSQLHeader()
    {
        $header = "-- ═══════════════════════════════════════════════════════════════════════════════════════\n";
        $header .= "-- PostgreSQL Database Export\n";
        $header .= "-- Converted from MySQL dump\n";
        $header .= "-- Conversion Date: " . date('Y-m-d H:i:s') . "\n";
        $header .= "-- ═══════════════════════════════════════════════════════════════════════════════════════\n\n";
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
        $footer .= "\n-- ═══════════════════════════════════════════════════════════════════════════════════════\n";
        $footer .= "-- اكتمل الاستيراد بنجاح\n";
        $footer .= "-- ═══════════════════════════════════════════════════════════════════════════════════════\n";
        
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
        echo "\n✓ تم حفظ الملف المحول في: {$outputFile}\n";
        echo "✓ يمكنك الآن استيراد الملف إلى PostgreSQL\n";
    } else {
        echo "\n" . str_repeat('═', 60) . "\n";
        echo $result;
    }
}
