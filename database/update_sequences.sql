-- ═══════════════════════════════════════════════════════════
-- PostgreSQL Sequences Update Script
-- سكريبت تحديث التسلسلات في PostgreSQL
-- ═══════════════════════════════════════════════════════════
--
-- هذا السكريبت يقوم بتحديث جميع sequences في قاعدة البيانات
-- بحيث تبدأ من القيمة الصحيحة بعد استيراد البيانات
--
-- الاستخدام:
-- psql -h host -U user -d database -f update_sequences.sql
--
-- ═══════════════════════════════════════════════════════════

-- تحديث Sequences تلقائياً لجميع الجداول
DO $$
DECLARE
    table_record RECORD;
    seq_name TEXT;
    max_id BIGINT;
BEGIN
    -- البحث عن جميع الجداول في schema العام
    FOR table_record IN 
        SELECT tablename 
        FROM pg_tables 
        WHERE schemaname = 'public'
        AND tablename NOT LIKE 'pg_%'
    LOOP
        -- بناء اسم sequence (افتراض أن الـ ID هو المفتاح الأساسي)
        seq_name := table_record.tablename || '_id_seq';
        
        -- التحقق من وجود عمود id
        IF EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_schema = 'public' 
            AND table_name = table_record.tablename 
            AND column_name = 'id'
        ) THEN
            -- الحصول على أكبر قيمة id
            EXECUTE format('SELECT COALESCE(MAX(id), 0) FROM %I', table_record.tablename) INTO max_id;
            
            -- التحقق من وجود sequence
            IF EXISTS (
                SELECT 1 
                FROM pg_class 
                WHERE relkind = 'S' 
                AND relname = seq_name
            ) THEN
                -- تحديث sequence
                EXECUTE format('SELECT setval(%L, %s, true)', seq_name, max_id);
                RAISE NOTICE 'Updated sequence % to %', seq_name, max_id;
            END IF;
        END IF;
    END LOOP;
END$$;

-- ═══════════════════════════════════════════════════════════
-- تحديثات يدوية لجداول محددة (إذا لزم الأمر)
-- ═══════════════════════════════════════════════════════════

-- مثال: تحديث sequence لجدول معين
-- SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));

-- للتحقق من جميع sequences وقيمها الحالية:
SELECT 
    schemaname,
    sequencename,
    last_value,
    increment_by
FROM pg_sequences
WHERE schemaname = 'public'
ORDER BY sequencename;

-- ═══════════════════════════════════════════════════════════
-- انتهى السكريبت
-- ═══════════════════════════════════════════════════════════
