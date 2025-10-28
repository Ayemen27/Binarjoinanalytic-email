# 🔐 إعداد قاعدة البيانات - Database Setup

## ⚠️ هام جداً - IMPORTANT

ملف `setup-env.sh` يحتوي على بيانات حساسة (**كلمات مرور**) ولذلك:
- ❌ **لا يُرفع للمستودع** (موجود في .gitignore)
- ✅ **يُستخدم على VPS فقط**
- ✅ **يُنفذ مرة واحدة** عند الإعداد الأولي

---

## 🚀 الاستخدام على VPS

### المرة الأولى فقط:

```bash
cd /home/administrator/panel

# 1. إنشاء ملف setup-env.sh يدوياً (نسخ المحتوى من هنا)
nano setup-env.sh
# الصق المحتوى التالي ثم احفظ (Ctrl+O ثم Ctrl+X)

# 2. اجعل الملف قابل للتنفيذ
chmod +x setup-env.sh

# 3. نفذ السكربت
sudo bash setup-env.sh

# 4. احذف السكربت بعد الاستخدام (اختياري للأمان)
rm setup-env.sh
```

---

## 📝 محتوى setup-env.sh (للنسخ)

```bash
#!/bin/bash

set -e

echo "🔐 Setting up .env file with database credentials..."

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_FILE="$PROJECT_DIR/.env"

# Database credentials
DB_HOST="127.0.0.1"
DB_DATABASE="temail_data"
DB_USERNAME="postgres"
DB_PASSWORD="Ay**--772283228"
DB_PORT="5432"

# Check if .env exists
if [ ! -f "$ENV_FILE" ]; then
    echo "Creating .env file..."
    if [ -f "$PROJECT_DIR/.env.example" ]; then
        cp "$PROJECT_DIR/.env.example" "$ENV_FILE"
    else
        touch "$ENV_FILE"
    fi
fi

# Function to update or add env variable
update_env() {
    local key=$1
    local value=$2
    
    if grep -q "^${key}=" "$ENV_FILE"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$ENV_FILE"
    else
        echo "${key}=${value}" >> "$ENV_FILE"
    fi
}

# Update database settings
update_env "DB_CONNECTION" "pgsql"
update_env "DB_HOST" "$DB_HOST"
update_env "DB_PORT" "$DB_PORT"
update_env "DB_DATABASE" "$DB_DATABASE"
update_env "DB_USERNAME" "$DB_USERNAME"
update_env "DB_PASSWORD" "$DB_PASSWORD"

chmod 644 "$ENV_FILE"
[ "$EUID" -eq 0 ] && chown www-data:www-data "$ENV_FILE"

echo "✅ .env updated successfully!"
echo "Database: $DB_DATABASE at $DB_HOST:$DB_PORT"
```

---

## 🔄 بعد إعداد .env

نفذ السكربت الرئيسي:

```bash
sudo bash deploy.sh
```

---

## ✅ التحقق من الاتصال

```bash
# اختبار الاتصال بقاعدة البيانات
sudo -u www-data php artisan migrate:status

# أو
sudo -u www-data php artisan tinker
# ثم اكتب:
DB::connection()->getPdo();
```

---

## 🔒 الأمان

**لماذا لا نرفع setup-env.sh للمستودع؟**
- يحتوي على **كلمة مرور حقيقية**
- قد يُسرب إذا كان المستودع عام
- يجب حفظ البيانات الحساسة **خارج Git**

**البدائل الآمنة:**
1. ✅ إنشاء السكربت محلياً على VPS فقط
2. ✅ استخدام متغيرات البيئة للسيرفر
3. ✅ استخدام أدوات إدارة الأسرار (Secrets Management)

---

## 📋 الخلاصة

| الخطوة | الأمر |
|--------|-------|
| 1. إنشاء setup-env.sh | `nano setup-env.sh` |
| 2. جعله قابل للتنفيذ | `chmod +x setup-env.sh` |
| 3. تنفيذ الإعداد | `sudo bash setup-env.sh` |
| 4. النشر الكامل | `sudo bash deploy.sh` |
| 5. حذف السكربت (اختياري) | `rm setup-env.sh` |

---

**💡 نصيحة:** احفظ كلمة المرور في مكان آمن (Password Manager) ولا تشاركها في الكود أبداً!
