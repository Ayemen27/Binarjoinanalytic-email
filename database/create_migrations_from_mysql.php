#!/usr/bin/env php
<?php

/**
 * MySQL Schema to Laravel Migrations Generator
 * يولد ملفات Laravel Migration من مخطط MySQL
 */

class MigrationGenerator
{
    private $tables = [];
    private $foreignKeys = [];
    private $migrationPath;
    private $timestamp;
    
    private $typeMap = [
        'bigint unsigned' => 'unsignedBigInteger',
        'bigint' => 'bigInteger',
        'int unsigned' => 'unsignedInteger',
        'int' => 'integer',
        'mediumint unsigned' => 'unsignedMediumInteger',
        'mediumint' => 'mediumInteger',
        'smallint unsigned' => 'unsignedSmallInteger',
        'smallint' => 'smallInteger',
        'tinyint(1)' => 'boolean',
        'tinyint unsigned' => 'unsignedTinyInteger',
        'tinyint' => 'tinyInteger',
        'varchar' => 'string',
        'text' => 'text',
        'longtext' => 'longText',
        'mediumtext' => 'mediumText',
        'tinytext' => 'text',
        'datetime' => 'dateTime',
        'timestamp' => 'timestamp',
        'date' => 'date',
        'time' => 'time',
        'decimal' => 'decimal',
        'double' => 'double',
        'float' => 'float',
        'json' => 'json',
    ];
    
    public function __construct()
    {
        $this->migrationPath = __DIR__ . '/migrations';
        if (!is_dir($this->migrationPath)) {
            mkdir($this->migrationPath, 0755, true);
        }
        $this->timestamp = date('Y_m_d_000000');
    }
    
    public function parseMySQL Schema($sqlContent)
    {
        echo "تحليل مخطط MySQL...\n";
        
        // استخراج CREATE TABLE statements
        preg_match_all(
            '/CREATE TABLE `(\w+)`\s*\((.*?)\)\s*ENGINE/is',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $tableName = $match[1];
            $tableContent = $match[2];
            
            echo "  → العثور على جدول: {$tableName}\n";
            
            $this->tables[$tableName] = $this->parseTableStructure($tableContent);
        }
        
        // استخراج FOREIGN KEYS
        $this->extractForeignKeys($sqlContent);
        
        echo "تم العثور على " . count($this->tables) . " جدول\n\n";
    }
    
    private function parseTableStructure($content)
    {
        $structure = [
            'columns' => [],
            'indexes' => [],
            'primary' => null,
            'unique' => [],
        ];
        
        // تقسيم الأسطر
        $lines = explode(',', $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // تخطي الأسطر الفارغة
            if (empty($line)) continue;
            
            // Primary Key
            if (preg_match('/PRIMARY KEY\s*\(`(\w+)`\)/i', $line, $m)) {
                $structure['primary'] = $m[1];
                continue;
            }
            
            // Unique Key
            if (preg_match('/UNIQUE KEY\s*`(\w+)`\s*\(`(\w+)`\)/i', $line, $m)) {
                $structure['unique'][] = [
                    'name' => $m[1],
                    'column' => $m[2]
                ];
                continue;
            }
            
            // Index
            if (preg_match('/KEY\s*`(\w+)`\s*\(`(\w+)`\)/i', $line, $m)) {
                $structure['indexes'][] = [
                    'name' => $m[1],
                    'column' => $m[2]
                ];
                continue;
            }
            
            // Column definition
            if (preg_match('/`(\w+)`\s+(\w+(?:\(\d+(?:,\d+)?\))?)/i', $line, $m)) {
                $columnName = $m[1];
                $columnType = strtolower($m[2]);
                
                $column = [
                    'name' => $columnName,
                    'type' => $this->mapColumnType($columnType),
                    'nullable' => !preg_match('/NOT NULL/i', $line),
                    'default' => $this->extractDefault($line),
                    'auto_increment' => preg_match('/AUTO_INCREMENT/i', $line),
                ];
                
                // استخراج الطول للـ varchar
                if (preg_match('/varchar\((\d+)\)/i', $columnType, $lengthMatch)) {
                    $column['length'] = $lengthMatch[1];
                }
                
                // استخراج الدقة للـ decimal
                if (preg_match('/decimal\((\d+),(\d+)\)/i', $columnType, $precisionMatch)) {
                    $column['precision'] = [$precisionMatch[1], $precisionMatch[2]];
                }
                
                $structure['columns'][] = $column;
            }
        }
        
        return $structure;
    }
    
    private function mapColumnType($mysqlType)
    {
        // إزالة الأقواس للمقارنة
        $baseType = preg_replace('/\(.*?\)/', '', $mysqlType);
        
        foreach ($this->typeMap as $pattern => $laravelType) {
            if (stripos($baseType, $pattern) !== false || $baseType === $pattern) {
                return $laravelType;
            }
        }
        
        return 'string'; // default
    }
    
    private function extractDefault($line)
    {
        if (preg_match('/DEFAULT\s+\'([^\']+)\'/i', $line, $m)) {
            return $m[1];
        }
        if (preg_match('/DEFAULT\s+(\w+)/i', $line, $m)) {
            return strtoupper($m[1]) === 'NULL' ? null : $m[1];
        }
        return null;
    }
    
    private function extractForeignKeys($sqlContent)
    {
        preg_match_all(
            '/ALTER TABLE `(\w+)`\s+ADD CONSTRAINT `(\w+)` FOREIGN KEY \(`(\w+)`\) REFERENCES `(\w+)` \(`(\w+)`\)(?:\s+ON DELETE (\w+))?(?:\s+ON UPDATE (\w+))?/i',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $this->foreignKeys[] = [
                'table' => $match[1],
                'name' => $match[2],
                'column' => $match[3],
                'references_table' => $match[4],
                'references_column' => $match[5],
                'on_delete' => $match[6] ?? 'RESTRICT',
                'on_update' => $match[7] ?? 'RESTRICT',
            ];
        }
    }
    
    public function generateMigrations()
    {
        echo "إنشاء ملفات Migration...\n";
        
        $counter = 1;
        foreach ($this->tables as $tableName => $structure) {
            $timestamp = date('Y_m_d_') . sprintf('%06d', $counter++);
            $filename = "{$timestamp}_create_{$tableName}_table.php";
            $filepath = $this->migrationPath . '/' . $filename;
            
            $migrationCode = $this->generateMigrationCode($tableName, $structure);
            
            file_put_contents($filepath, $migrationCode);
            echo "  ✓ {$filename}\n";
        }
        
        // إنشاء migration للـ foreign keys
        if (!empty($this->foreignKeys)) {
            $timestamp = date('Y_m_d_') . sprintf('%06d', $counter);
            $filename = "{$timestamp}_add_foreign_keys.php";
            $filepath = $this->migrationPath . '/' . $filename;
            
            $migrationCode = $this->generateForeignKeysMigration();
            file_put_contents($filepath, $migrationCode);
            echo "  ✓ {$filename}\n";
        }
        
        echo "\n✓ تم إنشاء " . count($this->tables) . " migration بنجاح!\n";
    }
    
    private function generateMigrationCode($tableName, $structure)
    {
        $className = 'Create' . str_replace('_', '', ucwords($tableName, '_')) . 'Table';
        
        $code = "<?php\n\n";
        $code .= "use Illuminate\\Database\\Migrations\\Migration;\n";
        $code .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
        $code .= "use Illuminate\\Support\\Facades\\Schema;\n\n";
        $code .= "return new class extends Migration\n";
        $code .= "{\n";
        $code .= "    public function up(): void\n";
        $code .= "    {\n";
        $code .= "        Schema::create('{$tableName}', function (Blueprint \$table) {\n";
        
        // Columns
        foreach ($structure['columns'] as $column) {
            $code .= $this->generateColumnCode($column, $structure['primary']);
        }
        
        // Indexes
        foreach ($structure['unique'] as $unique) {
            $code .= "            \$table->unique('{$unique['column']}', '{$unique['name']}');\n";
        }
        
        foreach ($structure['indexes'] as $index) {
            $code .= "            \$table->index('{$index['column']}', '{$index['name']}');\n";
        }
        
        $code .= "        });\n";
        $code .= "    }\n\n";
        $code .= "    public function down(): void\n";
        $code .= "    {\n";
        $code .= "        Schema::dropIfExists('{$tableName}');\n";
        $code .= "    }\n";
        $code .= "};\n";
        
        return $code;
    }
    
    private function generateColumnCode($column, $primaryKey)
    {
        $code = "            \$table->{$column['type']}('{$column['name']}'";
        
        // إضافة الطول للـ string
        if (isset($column['length']) && $column['type'] === 'string') {
            $code .= ", {$column['length']}";
        }
        
        // إضافة الدقة للـ decimal
        if (isset($column['precision'])) {
            $code .= ", {$column['precision'][0]}, {$column['precision'][1]}";
        }
        
        $code .= ")";
        
        // Primary key with auto increment
        if ($column['name'] === $primaryKey && $column['auto_increment']) {
            // استخدام id() أو bigIncrements()
            return "            \$table->id('{$column['name']}');\n";
        }
        
        // Nullable
        if ($column['nullable']) {
            $code .= "->nullable()";
        }
        
        // Default value
        if ($column['default'] !== null) {
            if ($column['default'] === 'CURRENT_TIMESTAMP') {
                $code .= "->useCurrent()";
            } else {
                $code .= "->default('{$column['default']}')";
            }
        }
        
        $code .= ";\n";
        
        return $code;
    }
    
    private function generateForeignKeysMigration()
    {
        $code = "<?php\n\n";
        $code .= "use Illuminate\\Database\\Migrations\\Migration;\n";
        $code .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
        $code .= "use Illuminate\\Support\\Facades\\Schema;\n\n";
        $code .= "return new class extends Migration\n";
        $code .= "{\n";
        $code .= "    public function up(): void\n";
        $code .= "    {\n";
        
        // تجميع foreign keys حسب الجدول
        $keysByTable = [];
        foreach ($this->foreignKeys as $fk) {
            $keysByTable[$fk['table']][] = $fk;
        }
        
        foreach ($keysByTable as $tableName => $keys) {
            $code .= "        Schema::table('{$tableName}', function (Blueprint \$table) {\n";
            foreach ($keys as $fk) {
                $code .= "            \$table->foreign('{$fk['column']}', '{$fk['name']}')\n";
                $code .= "                  ->references('{$fk['references_column']}')\n";
                $code .= "                  ->on('{$fk['references_table']}')\n";
                $code .= "                  ->onDelete('" . strtolower($fk['on_delete']) . "')\n";
                $code .= "                  ->onUpdate('" . strtolower($fk['on_update']) . "');\n";
            }
            $code .= "        });\n\n";
        }
        
        $code .= "    }\n\n";
        $code .= "    public function down(): void\n";
        $code .= "    {\n";
        
        foreach ($keysByTable as $tableName => $keys) {
            $code .= "        Schema::table('{$tableName}', function (Blueprint \$table) {\n";
            foreach ($keys as $fk) {
                $code .= "            \$table->dropForeign('{$fk['name']}');\n";
            }
            $code .= "        });\n";
        }
        
        $code .= "    }\n";
        $code .= "};\n";
        
        return $code;
    }
}

// ════════════════════════════════════════════════════════════
// الاستخدام
// ════════════════════════════════════════════════════════════

if (php_sapi_name() === 'cli') {
    $generator = new MigrationGenerator();
    
    // قراءة ملف MySQL dump
    echo "════════════════════════════════════════════════════════════\n";
    echo "  MySQL to Laravel Migrations Generator\n";
    echo "════════════════════════════════════════════════════════════\n\n";
    
    echo "يرجى إدخال مسار ملف MySQL dump: ";
    $inputFile = trim(fgets(STDIN));
    
    if (!file_exists($inputFile)) {
        die("الملف غير موجود: {$inputFile}\n");
    }
    
    $sqlContent = file_get_contents($inputFile);
    
    $generator->parseMySQLSchema($sqlContent);
    $generator->generateMigrations();
    
    echo "\nتم حفظ الـ migrations في: database/migrations/\n";
    echo "════════════════════════════════════════════════════════════\n";
}
