# دليل تحويل قاعدة البيانات من MySQL إلى PostgreSQL

## نظرة عامة

هذا الدليل يساعدك على تحويل تطبيق Laravel من قاعدة بيانات MySQL إلى PostgreSQL.

## الأدوات المتوفرة

### 1. `mysql_to_postgresql_converter.php`
**الوظيفة:** تحويل ملف SQL dump من MySQL إلى PostgreSQL

**الاستخدام:**
```bash
php database/mysql_to_postgresql_converter.php input.sql output.sql
```

**ما يقوم به:**
- إزالة أوامر MySQL الخاصة (SET, START TRANSACTION, إلخ)
- تحويل أنواع البيانات (bigint UNSIGNED → BIGINT، tinyint(1) → BOOLEAN)
- تحويل AUTO_INCREMENT إلى SERIAL/BIGSERIAL
- إزالة ENGINE و CHARACTER SET و COLLATE
- تحويل المفاتيح والفهارس
- إضافة رأس وتذييل PostgreSQL

### 2. `create_migrations_from_mysql.php`
**الوظيفة:** إنشاء ملفات Laravel Migration من مخطط MySQL

**الاستخدام:**
```bash
php database/create_migrations_from_mysql.php
```

**ما يقوم به:**
- قراءة ملف MySQL dump
- تحليل بنية الجداول
- إنشاء ملفات migration لكل جدول
- إنشاء migration منفصل للمفاتيح الخارجية

---

## خطوات التحويل الموصى بها

### الخيار 1: استخدام Migrations (موصى به)

هذا الخيار أفضل لأنه يحافظ على التوافق مع Laravel ويسمح بإدارة قاعدة البيانات بشكل أفضل.

#### الخطوة 1: إنشاء Migrations من MySQL dump

```bash
# تشغيل مولد الـ migrations
php database/create_migrations_from_mysql.php

# عند السؤال، أدخل مسار ملف MySQL dump الخاص بك
```

سيتم إنشاء ملفات migration في `database/migrations/`

#### الخطوة 2: مراجعة وتعديل Migrations

افتح ملفات migration المولدة وتأكد من:
- صحة أنواع البيانات
- المفاتيح الأساسية
- الفهارس
- المفاتيح الخارجية

#### الخطوة 3: تشغيل Migrations

```bash
php artisan migrate:fresh
```

#### الخطوة 4: استيراد البيانات

استخدم pgloader أو قم بتحويل INSERT statements:

```bash
# باستخدام محول SQL
php database/mysql_to_postgresql_converter.php your_data.sql postgres_data.sql

# ثم استيراد البيانات
psql -h your_host -U your_user -d your_database -f postgres_data.sql
```

---

### الخيار 2: تحويل SQL dump مباشرة

إذا كنت تفضل استيراد SQL dump مباشرة:

#### الخطوة 1: تحويل ملف SQL

```bash
php database/mysql_to_postgresql_converter.php mysql_dump.sql postgres_dump.sql
```

#### الخطوة 2: مراجعة الملف المحول

افتح `postgres_dump.sql` وتحقق من:
- أن جميع أنواع البيانات صحيحة
- أن AUTO_INCREMENT تم تحويلها إلى SERIAL
- أن المفاتيح الخارجية سليمة

#### الخطوة 3: استيراد إلى PostgreSQL

```bash
psql -h 93.127.142.144 -U postgres -d temail_data -f postgres_dump.sql
```

---

### الخيار 3: استخدام pgloader (الأسرع والأكثر أماناً)

`pgloader` أداة متخصصة في نقل البيانات من MySQL إلى PostgreSQL.

#### الخطوة 1: تثبيت pgloader

```bash
# على Ubuntu/Debian
sudo apt-get install pgloader

# على macOS
brew install pgloader
```

#### الخطوة 2: إنشاء ملف تكوين

أنشئ ملف `pgloader.conf`:

```
LOAD DATABASE
    FROM mysql://user:password@localhost/tempmail
    INTO postgresql://postgres:password@93.127.142.144/temail_data

WITH include drop, create tables, create indexes, reset sequences

CAST type tinyint(1) to boolean drop typemod using tinyint-to-boolean,
     type datetime to timestamp drop default drop not null

EXCLUDING TABLE NAMES MATCHING ~<^_>;
```

#### الخطوة 3: تشغيل pgloader

```bash
pgloader pgloader.conf
```

---

## التحقق بعد التحويل

### 1. اختبار الاتصال

```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::select('SELECT version()');
```

### 2. التحقق من الجداول

```sql
-- عرض جميع الجداول
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';

-- عد الصفوف في جدول معين
SELECT COUNT(*) FROM users;
```

### 3. تحديث Sequences

بعد استيراد البيانات، قد تحتاج لتحديث sequences:

```sql
-- لكل جدول يحتوي على auto-increment
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
SELECT setval('admins_id_seq', (SELECT MAX(id) FROM admins));
-- وهكذا لباقي الجداول
```

أو استخدم سكريبت SQL تلقائي:

```sql
DO $$
DECLARE
    r RECORD;
BEGIN
    FOR r IN SELECT tablename FROM pg_tables WHERE schemaname = 'public'
    LOOP
        EXECUTE 'SELECT setval(''' || quote_ident(r.tablename || '_id_seq') || ''', 
                 COALESCE((SELECT MAX(id) FROM ' || quote_ident(r.tablename) || '), 1), 
                 false)' ;
    END LOOP;
END$$;
```

### 4. اختبار التطبيق

```bash
php artisan migrate:status
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

---

## الفروقات الهامة بين MySQL و PostgreSQL

### 1. الحساسية لحالة الأحرف (Case Sensitivity)

- **MySQL:** غير حساس بشكل افتراضي
- **PostgreSQL:** حساس بشكل افتراضي

```php
// في PostgreSQL، قد تحتاج لاستخدام:
User::where('email', 'ILIKE', '%example%') // بدلاً من LIKE
```

### 2. Boolean Values

- **MySQL:** 0 و 1
- **PostgreSQL:** true و false

### 3. JSON

- **MySQL:** JSON
- **PostgreSQL:** JSON و JSONB (موصى به)

### 4. AUTO_INCREMENT vs SERIAL

- **MySQL:** AUTO_INCREMENT
- **PostgreSQL:** SERIAL/BIGSERIAL أو GENERATED ALWAYS AS IDENTITY

---

## المشاكل الشائعة وحلولها

### المشكلة 1: خطأ في نوع البيانات

```
ERROR: column "id" is of type bigint but expression is of type integer
```

**الحل:**
```php
// في migration
$table->bigInteger('user_id')->unsigned(); // غير مدعوم
$table->unsignedBigInteger('user_id'); // صحيح
```

### المشكلة 2: Sequences غير محدثة

```
ERROR: duplicate key value violates unique constraint
```

**الحل:**
```sql
SELECT setval('table_name_id_seq', (SELECT MAX(id) FROM table_name));
```

### المشكلة 3: COLLATION

```
ERROR: collation "utf8mb4_unicode_ci" does not exist
```

**الحل:** إزالة جميع COLLATION من SQL أو migrations

---

## موارد إضافية

- [Laravel Database Documentation](https://laravel.com/docs/database)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [pgloader Documentation](https://pgloader.readthedocs.io/)

---

## ملاحظات هامة

1. **النسخ الاحتياطي:** احتفظ دائماً بنسخة احتياطية من قاعدة البيانات قبل التحويل
2. **البيئة التجريبية:** جرب التحويل في بيئة تطوير أولاً
3. **الأداء:** قد تحتاج لضبط indexes بعد التحويل لتحسين الأداء
4. **الترميز:** تأكد من استخدام UTF-8 في كلا القاعدتين

---

## الخطوات التالية

بعد إكمال التحويل:

1. ✅ اختبار جميع وظائف التطبيق
2. ✅ مراجعة وتحسين الاستعلامات
3. ✅ ضبط إعدادات PostgreSQL للأداء الأمثل
4. ✅ تحديث الوثائق
5. ✅ تدريب الفريق على الفروقات

---

**تم إعداده بواسطة:** Replit Agent  
**التاريخ:** <?php echo date('Y-m-d'); ?>
