#!/usr/bin/env php
<?php

/**
 * MySQL Schema to Laravel Migrations Generator - Improved Version
 * مولد محسّن لملفات Laravel Migration من مخطط MySQL
 */

class ImprovedMigrationGenerator
{
    private $tables = [];
    private $foreignKeys = [];
    private $migrationPath;
    
    private $typeMap = [
        'bigint unsigned' => 'unsignedBigInteger',
        'bigint' => 'bigInteger',
        'int unsigned' => 'unsignedInteger',
        'int' => 'integer',
        'mediumint unsigned' => 'unsignedMediumInteger',
        'mediumint' => 'mediumInteger',
        'smallint unsigned' => 'unsignedSmallInteger',
        'smallint' => 'smallInteger',
        'tinyint unsigned' => 'unsignedTinyInteger',
        'tinyint' => 'tinyInteger',
        'varchar' => 'string',
        'char' => 'char',
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
        'enum' => 'enum',
    ];
    
    public function __construct()
    {
        $this->migrationPath = __DIR__ . '/migrations';
        if (!is_dir($this->migrationPath)) {
            mkdir($this->migrationPath, 0755, true);
        }
    }
    
    public function parseMySQLSchema($sqlContent)
    {
        echo "تحليل مخطط MySQL...\n";
        
        // استخراج CREATE TABLE statements
        // نستخدم regex أكثر قوة يتعامل مع الأقواس والفواصل داخل التعريفات
        preg_match_all(
            '/CREATE\s+TABLE\s+`([^`]+)`\s*\((.*?)\)\s*(?:ENGINE|;)/is',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $tableName = $match[1];
            $tableContent = $match[2];
            
            echo "  → جدول: {$tableName}\n";
            
            $this->tables[$tableName] = $this->parseTableStructureSafe($tableContent, $tableName);
        }
        
        // استخراج PRIMARY KEY و AUTO_INCREMENT من ALTER TABLE
        $this->extractAlterTableStatements($sqlContent);
        
        // استخراج FOREIGN KEYS من ALTER TABLE
        $this->extractForeignKeys($sqlContent);
        
        // استخراج FOREIGN KEYS من داخل CREATE TABLE
        $this->extractInlineForeignKeys($sqlContent);
        
        echo "\n✓ تم العثور على " . count($this->tables) . " جدول\n";
        if (!empty($this->foreignKeys)) {
            echo "✓ تم العثور على " . count($this->foreignKeys) . " مفتاح خارجي\n";
        }
        echo "\n";
    }
    
    private function parseTableStructureSafe($content, $tableName)
    {
        $structure = [
            'columns' => [],
            'indexes' => [],
            'primary' => null,
            'unique' => [],
            'foreign_keys' => [],
        ];
        
        // تقسيم بطريقة آمنة: نعالج الأقواس المتداخلة
        $lines = $this->splitSQLDefinitions($content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // PRIMARY KEY - يدعم single و composite
            if (preg_match('/PRIMARY\s+KEY\s*\(([^)]+)\)/i', $line, $m)) {
                // إزالة backticks
                $columns = str_replace('`', '', $m[1]);
                $structure['primary'] = array_map('trim', explode(',', $columns));
                continue;
            }
            
            // UNIQUE KEY - يدعم composite
            if (preg_match('/UNIQUE\s+KEY\s+`([^`]+)`\s*\(([^)]+)\)/i', $line, $m)) {
                $structure['unique'][] = [
                    'name' => $m[1],
                    'columns' => array_map(function($col) {
                        return trim(str_replace('`', '', $col));
                    }, explode(',', $m[2]))
                ];
                continue;
            }
            
            // INDEX / KEY - يدعم composite
            if (preg_match('/(?:KEY|INDEX)\s+`([^`]+)`\s*\(([^)]+)\)/i', $line, $m)) {
                $structure['indexes'][] = [
                    'name' => $m[1],
                    'columns' => array_map(function($col) {
                        return trim(str_replace('`', '', $col));
                    }, explode(',', $m[2]))
                ];
                continue;
            }
            
            // FOREIGN KEY (inline in CREATE TABLE)
            if (preg_match('/CONSTRAINT\s+`([^`]+)`\s+FOREIGN\s+KEY\s+\(`([^`]+)`\)\s+REFERENCES\s+`([^`]+)`\s+\(`([^`]+)`\)(?:\s+ON\s+DELETE\s+(SET\s+NULL|SET\s+DEFAULT|NO\s+ACTION|CASCADE|RESTRICT))?(?:\s+ON\s+UPDATE\s+(SET\s+NULL|SET\s+DEFAULT|NO\s+ACTION|CASCADE|RESTRICT))?/i', $line, $m)) {
                $structure['foreign_keys'][] = [
                    'name' => $m[1],
                    'column' => $m[2],
                    'references_table' => $m[3],
                    'references_column' => $m[4],
                    'on_delete' => $this->normalizeForeignKeyAction($m[5] ?? 'restrict'),
                    'on_update' => $this->normalizeForeignKeyAction($m[6] ?? 'restrict'),
                ];
                continue;
            }
            
            // Column definition
            // نتحقق أن السطر يبدأ بـ `column_name`
            if (preg_match('/^`([^`]+)`\s+(.+)$/i', $line, $m)) {
                $columnName = $m[1];
                $definition = $m[2];
                
                $column = $this->parseColumnDefinition($columnName, $definition);
                if ($column) {
                    $structure['columns'][] = $column;
                }
            }
        }
        
        return $structure;
    }
    
    /**
     * تقسيم محتوى CREATE TABLE بطريقة آمنة تراعي الأقواس المتداخلة
     */
    private function splitSQLDefinitions($content)
    {
        $lines = [];
        $current = '';
        $parenLevel = 0;
        $inString = false;
        $stringChar = null;
        
        for ($i = 0; $i < strlen($content); $i++) {
            $char = $content[$i];
            
            // التعامل مع strings
            if (($char === '"' || $char === "'") && ($i === 0 || $content[$i-1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                    $stringChar = null;
                }
            }
            
            if (!$inString) {
                if ($char === '(') {
                    $parenLevel++;
                } elseif ($char === ')') {
                    $parenLevel--;
                }
                
                // الفاصلة في المستوى الأساسي تعني نهاية التعريف
                if ($char === ',' && $parenLevel === 0) {
                    $lines[] = trim($current);
                    $current = '';
                    continue;
                }
            }
            
            $current .= $char;
        }
        
        // إضافة آخر سطر
        if (!empty(trim($current))) {
            $lines[] = trim($current);
        }
        
        return $lines;
    }
    
    private function parseColumnDefinition($columnName, $definition)
    {
        $column = [
            'name' => $columnName,
            'type' => null,
            'length' => null,
            'precision' => null,
            'nullable' => true,
            'default' => null,
            'auto_increment' => false,
            'unsigned' => false,
            'values' => null, // للـ enum
        ];
        
        // استخراج النوع
        // tinyint(1) خاص
        if (preg_match('/^tinyint\s*\(\s*1\s*\)/i', $definition)) {
            $column['type'] = 'boolean';
        }
        // enum('value1', 'value2', ...)
        elseif (preg_match('/^enum\s*\(([^)]+)\)/i', $definition, $m)) {
            $column['type'] = 'enum';
            // استخراج القيم
            $values = explode(',', $m[1]);
            $column['values'] = array_map(function($v) {
                return trim($v, " \t\n\r'\"");
            }, $values);
        }
        // decimal(10,2)
        elseif (preg_match('/^decimal\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/i', $definition, $m)) {
            $column['type'] = 'decimal';
            $column['precision'] = [$m[1], $m[2]];
        }
        // varchar(255)
        elseif (preg_match('/^varchar\s*\(\s*(\d+)\s*\)/i', $definition, $m)) {
            $column['type'] = 'string';
            $column['length'] = $m[1];
        }
        // أنواع أخرى مع أو بدون طول
        elseif (preg_match('/^(\w+)(?:\s*\(\s*(\d+)\s*\))?/i', $definition, $m)) {
            $baseType = strtolower($m[1]);
            
            // التحقق من unsigned
            if (preg_match('/\bunsigned\b/i', $definition)) {
                $column['unsigned'] = true;
                $baseType .= ' unsigned';
            }
            
            $column['type'] = $this->mapType($baseType);
            if (isset($m[2])) {
                $column['length'] = $m[2];
            }
        }
        
        // NOT NULL
        if (preg_match('/\bNOT\s+NULL\b/i', $definition)) {
            $column['nullable'] = false;
        }
        
        // AUTO_INCREMENT
        if (preg_match('/\bAUTO_INCREMENT\b/i', $definition)) {
            $column['auto_increment'] = true;
        }
        
        // DEFAULT
        if (preg_match('/\bDEFAULT\s+\'([^\']+)\'/i', $definition, $m)) {
            $column['default'] = $m[1];
        } elseif (preg_match('/\bDEFAULT\s+(\w+)/i', $definition, $m)) {
            $column['default'] = strtoupper($m[1]) === 'NULL' ? null : $m[1];
        }
        
        return $column;
    }
    
    private function mapType($mysqlType)
    {
        $mysqlType = strtolower(trim($mysqlType));
        
        foreach ($this->typeMap as $pattern => $laravelType) {
            if ($mysqlType === $pattern || stripos($mysqlType, $pattern) === 0) {
                return $laravelType;
            }
        }
        
        return 'string'; // default fallback
    }
    
    private function extractAlterTableStatements($sqlContent)
    {
        // استخراج PRIMARY KEY من ALTER TABLE
        preg_match_all(
            '/ALTER\s+TABLE\s+`([^`]+)`\s+ADD\s+PRIMARY\s+KEY\s+\(([^)]+)\)/i',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $tableName = $match[1];
            $columns = str_replace('`', '', $match[2]);
            $this->tables[$tableName]['primary'] = array_map('trim', explode(',', $columns));
        }
        
        // استخراج AUTO_INCREMENT من ALTER TABLE MODIFY
        preg_match_all(
            '/ALTER\s+TABLE\s+`([^`]+)`\s+MODIFY\s+`([^`]+)`\s+.*?AUTO_INCREMENT/i',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $tableName = $match[1];
            $columnName = $match[2];
            
            // تحديث العمود في الجدول
            if (isset($this->tables[$tableName])) {
                foreach ($this->tables[$tableName]['columns'] as &$column) {
                    if ($column['name'] === $columnName) {
                        $column['auto_increment'] = true;
                        break;
                    }
                }
            }
        }
        
        // استخراج UNIQUE KEY من ALTER TABLE
        preg_match_all(
            '/ALTER\s+TABLE\s+`([^`]+)`\s+ADD\s+UNIQUE\s+KEY\s+`([^`]+)`\s+\(([^)]+)\)/i',
            $sqlContent,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match) {
            $tableName = $match[1];
            $keyName = $match[2];
            $columns = str_replace('`', '', $match[3]);
            
            $this->tables[$tableName]['unique'][] = [
                'name' => $keyName,
                'columns' => array_map('trim', explode(',', $columns))
            ];
        }
    }
    
    private function normalizeForeignKeyAction($action)
    {
        if (empty($action)) {
            return 'restrict';
        }
        
        $action = strtolower(trim($action));
        
        // تطبيع الإجراءات المتعددة الكلمات
        $actionMap = [
            'set null' => 'set null',
            'no action' => 'no action',
            'cascade' => 'cascade',
            'restrict' => 'restrict',
            'set default' => 'set default',
        ];
        
        return $actionMap[$action] ?? 'restrict';
    }
    
    private function extractForeignKeys($sqlContent)
    {
        // regex محسّن يلتقط ON DELETE/UPDATE مع دعم العبارات متعددة الكلمات
        preg_match_all(
            '/ALTER\s+TABLE\s+`([^`]+)`\s+ADD\s+CONSTRAINT\s+`([^`]+)`\s+FOREIGN\s+KEY\s+\(`([^`]+)`\)\s+REFERENCES\s+`([^`]+)`\s+\(`([^`]+)`\)(?:\s+ON\s+DELETE\s+(SET\s+NULL|SET\s+DEFAULT|NO\s+ACTION|CASCADE|RESTRICT))?(?:\s+ON\s+UPDATE\s+(SET\s+NULL|SET\s+DEFAULT|NO\s+ACTION|CASCADE|RESTRICT))?/i',
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
                'on_delete' => $this->normalizeForeignKeyAction($match[6] ?? 'restrict'),
                'on_update' => $this->normalizeForeignKeyAction($match[7] ?? 'restrict'),
            ];
        }
    }
    
    private function extractInlineForeignKeys($sqlContent)
    {
        // جمع foreign keys من داخل الجداول
        foreach ($this->tables as $tableName => $structure) {
            if (!empty($structure['foreign_keys'])) {
                foreach ($structure['foreign_keys'] as $fk) {
                    $fk['table'] = $tableName;
                    $this->foreignKeys[] = $fk;
                }
            }
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
        
        // Foreign keys migration
        if (!empty($this->foreignKeys)) {
            $timestamp = date('Y_m_d_') . sprintf('%06d', $counter);
            $filename = "{$timestamp}_add_foreign_keys.php";
            $filepath = $this->migrationPath . '/' . $filename;
            
            $migrationCode = $this->generateForeignKeysMigration();
            file_put_contents($filepath, $migrationCode);
            echo "  ✓ {$filename}\n";
        }
        
        echo "\n✓ تم إنشاء " . (count($this->tables) + (!empty($this->foreignKeys) ? 1 : 0)) . " migration بنجاح!\n";
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
        
        // Primary key (composite)
        if (is_array($structure['primary']) && count($structure['primary']) > 1) {
            $cols = "'" . implode("', '", $structure['primary']) . "'";
            $code .= "\n            \$table->primary([{$cols}]);\n";
        }
        
        // Unique constraints (composite)
        foreach ($structure['unique'] as $unique) {
            if (count($unique['columns']) > 1) {
                $cols = "'" . implode("', '", $unique['columns']) . "'";
                $code .= "            \$table->unique([{$cols}], '{$unique['name']}');\n";
            } else {
                $code .= "            \$table->unique('{$unique['columns'][0]}', '{$unique['name']}');\n";
            }
        }
        
        // Indexes (composite)
        foreach ($structure['indexes'] as $index) {
            if (count($index['columns']) > 1) {
                $cols = "'" . implode("', '", $index['columns']) . "'";
                $code .= "            \$table->index([{$cols}], '{$index['name']}');\n";
            } else {
                $code .= "            \$table->index('{$index['columns'][0]}', '{$index['name']}');\n";
            }
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
        $name = $column['name'];
        $type = $column['type'];
        
        // تعامل خاص مع id auto-increment
        if (is_array($primaryKey) && count($primaryKey) === 1 && $primaryKey[0] === $name && $column['auto_increment']) {
            if ($type === 'unsignedBigInteger' || $type === 'bigInteger') {
                return "            \$table->id('{$name}');\n";
            } elseif ($type === 'unsignedInteger' || $type === 'integer') {
                return "            \$table->id('{$name}')->integer();\n";
            }
        }
        
        // بناء الكود
        $code = "            \$table->{$type}('{$name}'";
        
        // Parameters
        if ($type === 'string' && $column['length']) {
            $code .= ", {$column['length']}";
        } elseif ($type === 'decimal' && $column['precision']) {
            $code .= ", {$column['precision'][0]}, {$column['precision'][1]}";
        } elseif ($type === 'enum' && $column['values']) {
            $values = "['" . implode("', '", $column['values']) . "']";
            $code .= ", {$values}";
        }
        
        $code .= ")";
        
        // Modifiers
        if ($column['nullable']) {
            $code .= "->nullable()";
        }
        
        if ($column['default'] !== null) {
            if ($column['default'] === 'CURRENT_TIMESTAMP') {
                $code .= "->useCurrent()";
            } elseif (is_numeric($column['default'])) {
                $code .= "->default({$column['default']})";
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
                $code .= "                  ->onDelete('{$fk['on_delete']}')\n";
                $code .= "                  ->onUpdate('{$fk['on_update']}');\n";
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
// Main execution
// ════════════════════════════════════════════════════════════

if (php_sapi_name() === 'cli') {
    echo "════════════════════════════════════════════════════════════\n";
    echo "  MySQL to Laravel Migrations Generator (Improved)\n";
    echo "════════════════════════════════════════════════════════════\n\n";
    
    $generator = new ImprovedMigrationGenerator();
    
    if ($argc > 1) {
        $inputFile = $argv[1];
    } else {
        echo "يرجى إدخال مسار ملف MySQL dump: ";
        $inputFile = trim(fgets(STDIN));
    }
    
    if (!file_exists($inputFile)) {
        die("✗ الملف غير موجود: {$inputFile}\n");
    }
    
    $sqlContent = file_get_contents($inputFile);
    
    $generator->parseMySQLSchema($sqlContent);
    $generator->generateMigrations();
    
    echo "\nتم حفظ الـ migrations في: " . realpath(__DIR__ . '/migrations') . "/\n";
    echo "════════════════════════════════════════════════════════════\n";
}
