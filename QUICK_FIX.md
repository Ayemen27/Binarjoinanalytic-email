# 🚨 إصلاح سريع لمشكلة Class Not Found

## المشكلة
```
Class "Lobage\Planify\Models\Plan" not found
```

---

## ✅ الحل السريع (نفذ على VPS)

### 1️⃣ إصلاح الصلاحيات والـ Cache

```bash
# انتقل لمجلد المشروع
cd /home/administrator/panel

# ضبط الصلاحيات
sudo chown -R www-data:www-data .
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;
sudo chmod -R 775 storage bootstrap/cache

# إعادة تحميل Composer
sudo -u www-data composer dump-autoload -o

# مسح Laravel Cache
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# إعادة تحميل PHP-FPM (اختر الإصدار المناسب)
sudo systemctl reload php8.2-fpm
# أو
sudo systemctl reload php8.3-fpm
# أو
sudo systemctl reload php8.1-fpm
```

### 2️⃣ التحقق من الحل

```bash
# التحقق من تحميل الـ Class
sudo -u www-data php -r "require 'vendor/autoload.php'; echo class_exists('Lobage\\Planify\\Models\\Plan') ? '✅ تم حل المشكلة' : '❌ المشكلة مازالت موجودة'; echo PHP_EOL;"
```

### 3️⃣ اختبار التطبيق

افتح المتصفح وجرب الموقع

---

## 🔄 للحلول المستقبلية التلقائية

استخدم ملف `deploy.sh` بدلاً من الأوامر اليدوية:

```bash
# جعل السكربت قابل للتنفيذ (مرة واحدة)
chmod +x deploy.sh

# تشغيل السكربت (في كل مرة تسحب فيها تحديثات)
sudo bash deploy.sh
```

---

## 📖 لمزيد من التفاصيل

راجع ملف `DEPLOYMENT.md` للحصول على:
- شرح مفصل لكل خطوة
- إعداد Git Hooks التلقائي
- استكشاف الأخطاء المتقدم
- نصائح الأمان والأداء
