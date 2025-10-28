#!/bin/bash

# Laravel Deployment Script
# This script automates deployment and fixes permissions automatically

set -e

echo "🚀 Starting deployment process..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
PHP_USER="www-data"
PHP_GROUP="www-data"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo -e "${YELLOW}📁 Project directory: $PROJECT_DIR${NC}"

# Step 1: Pull latest changes
echo -e "${GREEN}1. Pulling latest changes from repository...${NC}"

# Fix git ownership issue if running as root
if [ "$EUID" -eq 0 ]; then
    # Temporarily restore ownership to current git user for pulling
    CURRENT_USER=$(stat -c '%U' .git 2>/dev/null || echo "administrator")
    if [ "$CURRENT_USER" != "root" ]; then
        chown -R $CURRENT_USER:$CURRENT_USER .git 2>/dev/null || true
    fi
    
    # Add safe directory to avoid dubious ownership error
    git config --global --add safe.directory "$PROJECT_DIR" 2>/dev/null || true
fi

git pull origin main 2>/dev/null || git pull origin master 2>/dev/null || echo "Git pull skipped"

# Step 2: Create necessary directories if they don't exist
echo -e "${GREEN}2. Creating necessary directories...${NC}"
mkdir -p "$PROJECT_DIR/vendor"
mkdir -p "$PROJECT_DIR/storage/framework/sessions"
mkdir -p "$PROJECT_DIR/storage/framework/views"
mkdir -p "$PROJECT_DIR/storage/framework/cache"
mkdir -p "$PROJECT_DIR/storage/logs"
mkdir -p "$PROJECT_DIR/bootstrap/cache"
echo "✓ Directories created"

# Step 3: Set correct ownership FIRST (before composer install)
echo -e "${GREEN}3. Setting correct file ownership...${NC}"
if [ "$EUID" -eq 0 ]; then
    chown -R $PHP_USER:$PHP_GROUP "$PROJECT_DIR"
    echo "✓ Ownership set to $PHP_USER:$PHP_GROUP"
else
    echo -e "${YELLOW}⚠ Run as root to set ownership. Skipping...${NC}"
fi

# Step 4: Set correct permissions
echo -e "${GREEN}4. Setting correct file permissions...${NC}"
# Directories
find "$PROJECT_DIR" -type d -exec chmod 755 {} \;
# Files
find "$PROJECT_DIR" -type f -exec chmod 644 {} \;
# Storage and cache directories need write permissions
chmod -R 775 "$PROJECT_DIR/storage"
chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
chmod -R 775 "$PROJECT_DIR/vendor" 2>/dev/null || true
# Make deploy.sh executable
chmod +x "$PROJECT_DIR/deploy.sh"

echo "✓ Permissions set correctly"

# Step 5: Install/Update Composer dependencies
echo -e "${GREEN}5. Installing Composer dependencies...${NC}"
if [ "$EUID" -eq 0 ]; then
    sudo -u $PHP_USER composer install --no-dev --optimize-autoloader --no-interaction
else
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Step 6: Clear and regenerate caches
echo -e "${GREEN}6. Clearing Laravel caches...${NC}"
if [ "$EUID" -eq 0 ]; then
    sudo -u $PHP_USER php artisan config:clear
    sudo -u $PHP_USER php artisan cache:clear
    sudo -u $PHP_USER php artisan view:clear
    sudo -u $PHP_USER php artisan route:clear
    sudo -u $PHP_USER php artisan optimize:clear
else
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    php artisan optimize:clear
fi

# Step 7: Regenerate autoload files
echo -e "${GREEN}7. Regenerating Composer autoload...${NC}"
if [ "$EUID" -eq 0 ]; then
    sudo -u $PHP_USER composer dump-autoload -o
else
    composer dump-autoload -o
fi

# Step 8: Optimize Laravel
echo -e "${GREEN}8. Optimizing Laravel...${NC}"
if [ "$EUID" -eq 0 ]; then
    sudo -u $PHP_USER php artisan config:cache
    sudo -u $PHP_USER php artisan route:cache
    sudo -u $PHP_USER php artisan view:cache
else
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Step 9: Reload PHP-FPM to clear OPcache
echo -e "${GREEN}9. Reloading PHP-FPM...${NC}"
if [ "$EUID" -eq 0 ]; then
    # Try to detect PHP version and reload
    for php_version in 8.3 8.2 8.1 8.0; do
        if systemctl list-units --type=service | grep -q "php${php_version}-fpm"; then
            systemctl reload "php${php_version}-fpm"
            echo "✓ PHP ${php_version}-FPM reloaded"
            break
        fi
    done
else
    echo -e "${YELLOW}⚠ Run as root to reload PHP-FPM. Skipping...${NC}"
fi

# Step 10: Verify autoload
echo -e "${GREEN}10. Verifying Lobage\Planify\Models\Plan class...${NC}"
if [ "$EUID" -eq 0 ]; then
    sudo -u $PHP_USER php -r "require 'vendor/autoload.php'; echo class_exists('Lobage\\Planify\\Models\\Plan') ? '✓ Class loaded successfully' : '✗ Class not found'; echo PHP_EOL;"
else
    php -r "require 'vendor/autoload.php'; echo class_exists('Lobage\\Planify\\Models\\Plan') ? '✓ Class loaded successfully' : '✗ Class not found'; echo PHP_EOL;"
fi

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo ""
echo "Next steps:"
echo "  - Test your application"
echo "  - Check logs: tail -f storage/logs/laravel.log"
