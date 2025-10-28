# دليل النشر التلقائي - Laravel VPS Deployment Guide

## 🎯 الهدف
هذا الدليل يساعدك على إعداد نظام نشر تلقائي يحل مشكلة الصلاحيات والـ cache بشكل دائم بعد كل `git pull`.

---

## 🚀 الإعداد الأولي (مرة واحدة فقط)

### 1. تفعيل Git Hook التلقائي

بعد سحب المشروع على الـ VPS، نفذ الأوامر التالية:

```bash
# الانتقال لمجلد المشروع
cd /home/administrator/panel

# نسخ الـ hook إلى مجلد git
cp hooks/post-merge .git/hooks/post-merge

# جعل الـ hook قابل للتنفيذ
chmod +x .git/hooks/post-merge

# جعل deploy.sh قابل للتنفيذ
chmod +x deploy.sh
```

### 2. تعديل الإعدادات في deploy.sh (إذا لزم الأمر)

افتح ملف `deploy.sh` وتحقق من المتغيرات التالية:

```bash
PHP_USER="www-data"        # مستخدم PHP-FPM (قد يكون nginx أو apache)
PHP_GROUP="www-data"       # مجموعة PHP-FPM
```

**لمعرفة المستخدم الصحيح على VPS:**
```bash
# تحقق من مستخدم PHP-FPM
ps aux | grep php-fpm | grep -v grep

# أو
cat /etc/php/8.2/fpm/pool.d/www.conf | grep "^user"
```

---

## 📦 كيف يعمل النظام التلقائي

### عند تنفيذ `git pull`:

1. ✅ يتم سحب آخر التحديثات
2. ✅ يتم تشغيل `.git/hooks/post-merge` تلقائياً
3. ✅ يتم تشغيل `deploy.sh` الذي يقوم بـ:
   - تثبيت/تحديث Composer dependencies
   - ضبط صلاحيات الملفات تلقائياً
   - مسح جميع الـ caches
   - إعادة تحميل PHP-FPM
   - التحقق من تحميل الـ classes

---

## 🔧 الاستخدام اليومي

### الطريقة الأولى (تلقائية - موصى بها):
```bash
git pull
# كل شيء يتم تلقائياً بفضل الـ hook!
```

### الطريقة الثانية (يدوية):
```bash
sudo bash deploy.sh
```

---

## 🛠️ إصلاح المشكلة الحالية (مرة واحدة)

إذا كانت المشكلة موجودة حالياً، نفذ هذه الأوامر:

```bash
# الانتقال لمجلد المشروع
cd /home/administrator/panel

# تشغيل سكربت النشر
sudo bash deploy.sh
```

---

## 📋 الصلاحيات الصحيحة في Laravel

### المجلدات والملفات:
- **المجلدات**: `755` (drwxr-xr-x)
- **الملفات**: `644` (-rw-r--r--)
- **storage/**: `775` (drwxrwxr-x) - قابل للكتابة
- **bootstrap/cache/**: `775` (drwxrwxr-x) - قابل للكتابة

### المالك:
- **المالك والمجموعة**: `www-data:www-data` (أو حسب إعداد PHP-FPM)

---

## 🔍 التحقق من حل المشكلة

### 1. التحقق من تحميل الـ Class:
```bash
sudo -u www-data php -r "require 'vendor/autoload.php'; var_dump(class_exists('Lobage\\Planify\\Models\\Plan'));"
```

**النتيجة المتوقعة:**
```
bool(true)
```

### 2. مراقبة اللوجات:
```bash
tail -f storage/logs/laravel.log
```

### 3. اختبار التطبيق:
افتح المتصفح وجرب الموقع

---

## ⚠️ استكشاف الأخطاء

### المشكلة: الـ Hook لا يعمل تلقائياً

**الحل:**
```bash
# تأكد من أن الـ hook قابل للتنفيذ
chmod +x .git/hooks/post-merge

# تأكد من أن deploy.sh قابل للتنفيذ
chmod +x deploy.sh
```

### المشكلة: Permission denied

**الحل:**
```bash
# تشغيل السكربت بصلاحيات root
sudo bash deploy.sh
```

### المشكلة: Class not found بعد Deploy

**الحل:**
```bash
# مسح OPcache يدوياً
sudo systemctl reload php8.2-fpm

# إعادة تحميل Composer autoload
composer dump-autoload -o

# مسح Laravel cache
php artisan optimize:clear
```

---

## 🎨 أوامر مفيدة إضافية

### مسح جميع الـ Caches:
```bash
php artisan optimize:clear
```

### إعادة بناء الـ Caches:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### التحقق من إعدادات PHP-FPM:
```bash
# معرفة إصدار PHP
php -v

# معرفة مستخدم PHP-FPM
ps aux | grep php-fpm | grep -v grep
```

### التحقق من صلاحيات الملفات:
```bash
# التحقق من صلاحيات storage
ls -la storage/

# التحقق من صلاحيات vendor
ls -la vendor/lobage/
```

---

## 📝 ملاحظات مهمة

1. **لا تعدل الملفات في `vendor/` يدوياً** - استخدم Composer دائماً
2. **السكربت آمن للتشغيل المتكرر** - يمكنك تشغيله في أي وقت
3. **Git Hook يعمل تلقائياً** بعد `git pull` أو `git merge`
4. **لا حاجة لتعديل الصلاحيات يدوياً** بعد الآن
5. **السكربت يدعم التشغيل كـ root أو مستخدم عادي**

---

## 🔐 الأمان

- السكربت يستخدم `www-data` كمستخدم افتراضي
- يتم ضبط الصلاحيات وفقاً لأفضل الممارسات
- لا يتم كشف معلومات حساسة
- OPcache يتم مسحه تلقائياً

---

## 💡 نصائح للإنتاج (Production)

1. **استخدم Environment Variables** للإعدادات الحساسة
2. **فعّل OPcache** في الإنتاج لتحسين الأداء
3. **استخدم Queue Workers** للمهام الثقيلة
4. **راقب اللوجات** بانتظام
5. **خذ نسخة احتياطية** قبل أي تحديث كبير

---

## 📞 الدعم

إذا واجهت أي مشاكل:
1. تحقق من اللوجات: `tail -f storage/logs/laravel.log`
2. تحقق من صلاحيات الملفات
3. تأكد من تشغيل PHP-FPM بشكل صحيح
4. جرب تشغيل `deploy.sh` يدوياً

---

## ✅ Checklist النشر

- [ ] نسخ post-merge hook إلى `.git/hooks/`
- [ ] جعل الـ hooks و deploy.sh قابلة للتنفيذ
- [ ] تعديل PHP_USER و PHP_GROUP إذا لزم الأمر
- [ ] تشغيل `deploy.sh` أول مرة
- [ ] التحقق من عمل التطبيق
- [ ] اختبار `git pull` والتأكد من تشغيل الـ hook تلقائياً

---

**تم إنشاؤها بواسطة:** Replit Agent  
**التاريخ:** 2025-10-28  
**الإصدار:** 1.0
