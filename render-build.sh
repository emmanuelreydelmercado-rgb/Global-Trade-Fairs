#!/usr/bin/env bash
# Render build script for Laravel

set -o errexit

# Install Composer dependencies
composer install --no-dev --working-dir=/opt/render/project/src --optimize-autoloader --no-interaction

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache Laravel configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
php artisan storage:link || true

echo "Build completed successfully!"
