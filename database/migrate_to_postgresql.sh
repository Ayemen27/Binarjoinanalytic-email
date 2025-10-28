#!/bin/bash

# ═══════════════════════════════════════════════════════════
# MySQL to PostgreSQL Migration Script
# سكريبت تحويل قاعدة البيانات من MySQL إلى PostgreSQL
# ═══════════════════════════════════════════════════════════

set -e  # الإيقاف عند أي خطأ

# الألوان للعرض
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # بدون لون

echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  MySQL to PostgreSQL Migration Wizard${NC}"
echo -e "${BLUE}  معالج تحويل قاعدة البيانات${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
echo ""

# التحقق من وجود PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP غير مثبت!${NC}"
    exit 1
fi

echo -e "${GREEN}✓ PHP متوفر: $(php -v | head -n1)${NC}"
echo ""

# قائمة الخيارات
echo "يرجى اختيار طريقة التحويل:"
echo ""
echo "  1. اختبار الاتصال بـ PostgreSQL"
echo "  2. إنشاء Laravel Migrations من MySQL dump"
echo "  3. تحويل ملف SQL من MySQL إلى PostgreSQL"
echo "  4. تحديث Sequences بعد استيراد البيانات"
echo "  5. تنفيذ التحويل الكامل (خطوات 2 + 3 + 4)"
echo "  6. الخروج"
echo ""
read -p "اختر رقماً [1-6]: " choice

case $choice in
    1)
        echo ""
        echo -e "${YELLOW}→ اختبار الاتصال بقاعدة بيانات PostgreSQL...${NC}"
        php database/test_connection.php
        ;;
    
    2)
        echo ""
        echo -e "${YELLOW}→ إنشاء Laravel Migrations من MySQL dump...${NC}"
        echo ""
        read -p "أدخل مسار ملف MySQL dump: " mysql_file
        
        if [ ! -f "$mysql_file" ]; then
            echo -e "${RED}✗ الملف غير موجود: $mysql_file${NC}"
            exit 1
        fi
        
        php database/create_migrations_from_mysql.php <<< "$mysql_file"
        
        echo ""
        echo -e "${GREEN}✓ تم إنشاء Migrations بنجاح!${NC}"
        echo -e "${BLUE}الخطوة التالية: راجع الملفات في database/migrations/${NC}"
        echo -e "${BLUE}ثم قم بتشغيل: php artisan migrate${NC}"
        ;;
    
    3)
        echo ""
        echo -e "${YELLOW}→ تحويل ملف SQL من MySQL إلى PostgreSQL...${NC}"
        echo ""
        read -p "أدخل مسار ملف MySQL SQL: " mysql_file
        read -p "أدخل مسار ملف PostgreSQL الناتج [${mysql_file%.sql}_postgres.sql]: " postgres_file
        
        if [ -z "$postgres_file" ]; then
            postgres_file="${mysql_file%.sql}_postgres.sql"
        fi
        
        if [ ! -f "$mysql_file" ]; then
            echo -e "${RED}✗ الملف غير موجود: $mysql_file${NC}"
            exit 1
        fi
        
        php database/mysql_to_postgresql_converter.php "$mysql_file" "$postgres_file"
        
        echo ""
        echo -e "${GREEN}✓ تم التحويل بنجاح!${NC}"
        echo -e "${BLUE}الملف المحول: $postgres_file${NC}"
        echo ""
        echo -e "${BLUE}الخطوة التالية: استيراد الملف إلى PostgreSQL${NC}"
        echo -e "${YELLOW}psql -h host -U user -d database -f $postgres_file${NC}"
        ;;
    
    4)
        echo ""
        echo -e "${YELLOW}→ تحديث Sequences...${NC}"
        echo ""
        read -p "أدخل host [localhost]: " pg_host
        read -p "أدخل port [5432]: " pg_port
        read -p "أدخل database name: " pg_database
        read -p "أدخل username [postgres]: " pg_user
        
        pg_host=${pg_host:-localhost}
        pg_port=${pg_port:-5432}
        pg_user=${pg_user:-postgres}
        
        if [ -z "$pg_database" ]; then
            echo -e "${RED}✗ يجب إدخال اسم قاعدة البيانات${NC}"
            exit 1
        fi
        
        echo ""
        echo -e "${YELLOW}تشغيل سكريبت تحديث Sequences...${NC}"
        psql -h "$pg_host" -p "$pg_port" -U "$pg_user" -d "$pg_database" -f database/update_sequences.sql
        
        echo ""
        echo -e "${GREEN}✓ تم تحديث Sequences بنجاح!${NC}"
        ;;
    
    5)
        echo ""
        echo -e "${YELLOW}→ تنفيذ التحويل الكامل...${NC}"
        echo ""
        echo -e "${BLUE}هذا سيقوم بـ:${NC}"
        echo -e "  1. إنشاء Laravel Migrations"
        echo -e "  2. تحويل ملف SQL"
        echo -e "  3. استيراد البيانات (يدوي)"
        echo -e "  4. تحديث Sequences"
        echo ""
        read -p "هل تريد المتابعة؟ [y/N]: " confirm
        
        if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
            echo -e "${YELLOW}تم الإلغاء${NC}"
            exit 0
        fi
        
        # الخطوة 1: Migrations
        echo ""
        echo -e "${BLUE}[1/4] إنشاء Migrations...${NC}"
        read -p "أدخل مسار ملف MySQL dump: " mysql_file
        
        if [ ! -f "$mysql_file" ]; then
            echo -e "${RED}✗ الملف غير موجود${NC}"
            exit 1
        fi
        
        php database/create_migrations_from_mysql.php <<< "$mysql_file"
        
        # الخطوة 2: تحويل SQL
        echo ""
        echo -e "${BLUE}[2/4] تحويل ملف SQL...${NC}"
        postgres_file="${mysql_file%.sql}_postgres.sql"
        php database/mysql_to_postgresql_converter.php "$mysql_file" "$postgres_file"
        
        # الخطوة 3: تعليمات الاستيراد
        echo ""
        echo -e "${BLUE}[3/4] استيراد البيانات...${NC}"
        echo -e "${YELLOW}يرجى تشغيل الأمر التالي يدوياً:${NC}"
        echo -e "${GREEN}psql -h host -U user -d database -f $postgres_file${NC}"
        echo ""
        read -p "اضغط Enter بعد اكتمال الاستيراد..."
        
        # الخطوة 4: تحديث Sequences
        echo ""
        echo -e "${BLUE}[4/4] تحديث Sequences...${NC}"
        read -p "أدخل host: " pg_host
        read -p "أدخل port [5432]: " pg_port
        read -p "أدخل database name: " pg_database
        read -p "أدخل username [postgres]: " pg_user
        
        pg_port=${pg_port:-5432}
        pg_user=${pg_user:-postgres}
        
        psql -h "$pg_host" -p "$pg_port" -U "$pg_user" -d "$pg_database" -f database/update_sequences.sql
        
        echo ""
        echo -e "${GREEN}✓✓✓ اكتمل التحويل بنجاح! ✓✓✓${NC}"
        echo ""
        echo -e "${BLUE}الخطوات التالية:${NC}"
        echo -e "  1. اختبار التطبيق: php artisan tinker"
        echo -e "  2. التحقق من البيانات: php database/test_connection.php"
        echo -e "  3. تشغيل Laravel: php artisan serve"
        ;;
    
    6)
        echo -e "${YELLOW}خروج...${NC}"
        exit 0
        ;;
    
    *)
        echo -e "${RED}✗ خيار غير صحيح${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}  تم بنجاح!${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════${NC}"
