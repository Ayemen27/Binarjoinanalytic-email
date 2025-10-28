#!/bin/bash

# Quick Permissions Fix Script
# Use this if you only need to fix permissions without full deployment

set -e

echo "🔐 Fixing Laravel file permissions..."

# Configuration
PHP_USER="www-data"
PHP_GROUP="www-data"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Set ownership
if [ "$EUID" -eq 0 ]; then
    echo "Setting ownership to $PHP_USER:$PHP_GROUP..."
    chown -R $PHP_USER:$PHP_GROUP "$PROJECT_DIR"
else
    echo "⚠ Run as root (sudo) to set ownership"
fi

# Set permissions
echo "Setting file permissions..."
find "$PROJECT_DIR" -type d -exec chmod 755 {} \;
find "$PROJECT_DIR" -type f -exec chmod 644 {} \;

# Storage and cache need write permissions
echo "Setting writable directories..."
chmod -R 775 "$PROJECT_DIR/storage"
chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

# Make scripts executable
chmod +x "$PROJECT_DIR/deploy.sh"
chmod +x "$PROJECT_DIR/fix-permissions.sh"
[ -f "$PROJECT_DIR/.git/hooks/post-merge" ] && chmod +x "$PROJECT_DIR/.git/hooks/post-merge"

echo "✅ Permissions fixed successfully!"

# Show current permissions
echo ""
echo "Current permissions:"
ls -la storage/ | head -5
ls -la bootstrap/cache/ | head -5
