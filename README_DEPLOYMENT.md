# 🚀 دليل النشر السريع - VPS Deployment

## 📌 نظرة عامة

هذا المشروع يحتوي على نظام نشر تلقائي يحل مشكلة:
- ❌ **Class "Lobage\Planify\Models\Plan" not found**
- ❌ **مشاكل الصلاحيات بعد git pull**
- ❌ **مشاكل الـ Cache على VPS**

---

## ⚡ البداية السريعة

### على VPS (الخطوات الأولى):

```bash
# 1. انتقل للمشروع
cd /home/administrator/panel

# 2. اسحب آخر التحديثات
git pull

# 3. نفذ السكربت
sudo bash deploy.sh
```

✅ **تم! المشكلة محلولة**

---

## 🔧 الإعداد التلقائي (مرة واحدة)

لجعل النشر تلقائياً بعد كل `git pull`:

```bash
# نسخ Git Hook
cp hooks/post-merge .git/hooks/post-merge
chmod +x .git/hooks/post-merge
```

الآن عند تنفيذ `git pull`، سيتم تشغيل `deploy.sh` تلقائياً! 🎉

---

## 📁 الملفات المهمة

| الملف | الوصف |
|-------|-------|
| `deploy.sh` | سكربت النشر الرئيسي |
| `hooks/post-merge` | Git Hook للتشغيل التلقائي |
| `DEPLOYMENT.md` | الدليل الكامل المفصل |
| `QUICK_FIX.md` | إصلاح سريع للمشكلة الحالية |

---

## ❓ ماذا يفعل deploy.sh؟

1. ✅ يسحب آخر التحديثات من Git
2. ✅ يثبت/يحدث Composer packages
3. ✅ يضبط صلاحيات الملفات تلقائياً
4. ✅ يمسح جميع الـ Caches (Config, Route, View)
5. ✅ يعيد تحميل Composer autoload
6. ✅ يعيد تحميل PHP-FPM لمسح OPcache
7. ✅ يتحقق من تحميل الـ Classes

---

## 🆘 حل المشاكل

### المشكلة موجودة الآن؟
```bash
sudo bash deploy.sh
```

### Git Hook لا يعمل؟
```bash
chmod +x .git/hooks/post-merge
chmod +x deploy.sh
```

### Class not found مازال موجود؟
```bash
# تحقق من PHP-FPM user
ps aux | grep php-fpm | grep -v grep

# عدل deploy.sh وغير:
# PHP_USER="www-data"  # ← هنا
```

---

## 📚 مزيد من المعلومات

- **الدليل الكامل:** افتح `DEPLOYMENT.md`
- **الحل السريع:** افتح `QUICK_FIX.md`

---

## ✅ مثال على النشر

```bash
# على VPS
cd /home/administrator/panel
git pull    # ← كل شيء يحدث تلقائياً هنا!

# أو يدوياً:
sudo bash deploy.sh
```

---

**💡 نصيحة:** ارفع هذه الملفات للمستودع حتى تكون متاحة في كل مرة تنسخ فيها المشروع!
