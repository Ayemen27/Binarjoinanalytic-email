#!/usr/bin/env php
<?php

/**
 * MySQL to PostgreSQL Converter
 * 
 * هذا السكريبت يحول ملفات SQL من MySQL إلى PostgreSQL
 */

class MySQLToPostgreSQLConverter
{
    private $mysqlFile;
    private $postgresFile;
    private $content;
    
    // خريطة تحويل أنواع البيانات
    private $typeMap = [
        'bigint UNSIGNED' => 'BIGINT',
        'int UNSIGNED' => 'INTEGER',
        'tinyint(1)' => 'BOOLEAN',
        'tinyint UNSIGNED' => 'SMALLINT',
        'smallint UNSIGNED' => 'SMALLINT',
        'mediumint UNSIGNED' => 'INTEGER',
        'longtext' => 'TEXT',
        'mediumtext' => 'TEXT',
        'tinytext' => 'TEXT',
        'datetime' => 'TIMESTAMP',
    ];
    
    public function __construct($mysqlFile, $postgresFile = null)
    {
        $this->mysqlFile = $mysqlFile;
        $this->postgresFile = $postgresFile ?? str_replace('.sql', '_postgres.sql', $mysqlFile);
        
        if (!file_exists($mysqlFile)) {
            throw new Exception("الملف غير موجود: {$mysqlFile}");
        }
        
        $this->content = file_get_contents($mysqlFile);
    }
    
    public function convert()
    {
        echo "بدء التحويل من MySQL إلى PostgreSQL...\n";
        
        // خطوة 1: إزالة أوامر MySQL الخاصة
        $this->removeMySQL

Specifics();
        
        // خطوة 2: تحويل أنواع البيانات
        $this->convertDataTypes();
        
        // خطوة 3: تحويل AUTO_INCREMENT إلى SERIAL
        $this->convertAutoIncrement();
        
        // خطوة 4: تحويل المفاتيح والفهارس
        $this->convertKeysAndIndexes();
        
        // خطوة 5: تحويل ENGINE و CHARSET
        $this->removeEngineAndCharset();
        
        // خطوة 6: معالجة INSERT statements
        $this->convertInsertStatements();
        
        // خطوة 7: إضافة رأس PostgreSQL
        $this->addPostgreSQLHeader();
        
        // حفظ الملف
        file_put_contents($this->postgresFile, $this->content);
        
        echo "✓ تم التحويل بنجاح!\n";
        echo "الملف المحول: {$this->postgresFile}\n";
    }
    
    private function removeMySQLSpecifics()
    {
        echo "إزالة أوامر MySQL الخاصة...\n";
        
        // إزالة التعليقات الخاصة بـ MySQL
        $this->content = preg_replace('/\/\*!40\d{3}.*?\*\/;?/s', '', $this->content);
        
        // إزالة أوامر SET الخاصة بـ MySQL
        $this->content = preg_replace('/SET SQL_MODE.*?;/i', '', $this->content);
        $this->content = preg_replace('/START TRANSACTION;/i', 'BEGIN;', $this->content);
        $this->content = preg_replace('/SET @OLD.*?;/i', '', $this->content);
        $this->content = preg_replace('/SET NAMES.*?;/i', '', $this->content);
        
        // إزالة backticks
        $this->content = str_replace('`', '"', $this->content);
    }
    
    private function convertDataTypes()
    {
        echo "تحويل أنواع البيانات...\n";
        
        foreach ($this->typeMap as $mysqlType => $postgresType) {
            $this->content = preg_replace(
                '/\b' . preg_quote($mysqlType, '/') . '\b/i',
                $postgresType,
                $this->content
            );
        }
        
        // تحويل varchar مع CHARACTER SET
        $this->content = preg_replace(
            '/varchar\((\d+)\)\s+CHARACTER SET.*?(?=,|\))/i',
            'VARCHAR($1)',
            $this->content
        );
        
        // إزالة DEFAULT CHARSET
        $this->content = preg_replace('/DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $this->content);
        $this->content = preg_replace('/COLLATE\s*=?\s*[\w_]+/i', '', $this->content);
    }
    
    private function convertAutoIncrement()
    {
        echo "تحويل AUTO_INCREMENT إلى SERIAL...\n";
        
        // البحث عن جميع الجداول وأعمدة AUTO_INCREMENT
        $pattern = '/CREATE TABLE "(\w+)".*?"(\w+)"\s+BIGINT.*?AUTO_INCREMENT/is';
        
        if (preg_match_all($pattern, $this->content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $tableName = $match[1];
                $columnName = $match[2];
                
                // استبدال BIGINT AUTO_INCREMENT بـ BIGSERIAL
                $this->content = preg_replace(
                    '/"' . $columnName . '"\s+BIGINT\s+NOT NULL\s+AUTO_INCREMENT/i',
                    '"' . $columnName . '" BIGSERIAL NOT NULL',
                    $this->content
                );
            }
        }
        
        // التعامل مع INTEGER AUTO_INCREMENT
        $this->content = preg_replace(
            '/INTEGER\s+NOT NULL\s+AUTO_INCREMENT/i',
            'SERIAL NOT NULL',
            $this->content
        );
    }
    
    private function convertKeysAndIndexes()
    {
        echo "تحويل المفاتيح والفهارس...\n";
        
        // تحويل ALTER TABLE ... ADD PRIMARY KEY
        $this->content = preg_replace(
            '/ALTER TABLE "(\w+)"\s+ADD PRIMARY KEY \("(\w+)"\);/i',
            'ALTER TABLE "$1" ADD CONSTRAINT "$1_pkey" PRIMARY KEY ("$2");',
            $this->content
        );
        
        // تحويل UNIQUE KEY
        $this->content = preg_replace(
            '/ADD UNIQUE KEY "(\w+)" \("(\w+)"\)/i',
            'ADD CONSTRAINT "$1" UNIQUE ("$2")',
            $this->content
        );
        
        // تحويل FOREIGN KEY
        $this->content = preg_replace(
            '/CONSTRAINT "(\w+)" FOREIGN KEY/i',
            'CONSTRAINT "$1" FOREIGN KEY',
            $this->content
        );
    }
    
    private function removeEngineAndCharset()
    {
        echo "إزالة ENGINE و CHARSET...\n";
        
        // إزالة ENGINE=InnoDB
        $this->content = preg_replace('/\)\s*ENGINE\s*=\s*\w+/i', ')', $this->content);
        
        // إزالة DEFAULT CHARSET
        $this->content = preg_replace('/DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $this->content);
        
        // إزالة CHARACTER SET من تعريفات الأعمدة
        $this->content = preg_replace('/CHARACTER SET\s+\w+/i', '', $this->content);
        
        // إزالة COLLATE
        $this->content = preg_replace('/COLLATE\s+[\w_]+/i', '', $this->content);
    }
    
    private function convertInsertStatements()
    {
        echo "تحويل جمل INSERT...\n";
        
        // لا حاجة لتغييرات كبيرة في جمل INSERT
        // لكن نحتاج للتأكد من عدم وجود backticks
        // تم التعامل معها في removeMySQLSpecifics
    }
    
    private function addPostgreSQLHeader()
    {
        $header = "-- PostgreSQL Database Export\n";
        $header .= "-- Converted from MySQL\n";
        $header .= "-- Conversion Date: " . date('Y-m-d H:i:s') . "\n\n";
        $header .= "-- Disable triggers and constraints during import\n";
        $header .= "SET session_replication_role = 'replica';\n\n";
        
        $this->content = $header . $this->content;
        
        // إضافة تذييل
        $footer = "\n\n-- Re-enable triggers and constraints\n";
        $footer .= "SET session_replication_role = 'origin';\n";
        
        $this->content .= $footer;
    }
}

// استخدام السكريبت
try {
    $inputFile = __DIR__ . '/mysql_dump.sql';
    $outputFile = __DIR__ . '/postgres_dump.sql';
    
    $converter = new MySQLToPostgreSQLConverter($inputFile, $outputFile);
    $converter->convert();
    
    echo "\n✓ اكتمل التحويل بنجاح!\n";
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    exit(1);
}
