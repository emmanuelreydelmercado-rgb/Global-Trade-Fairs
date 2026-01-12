#!/usr/bin/env bash
# exit on error
set -o errexit

echo "🔧 Installing Composer dependencies..."
composer install --no-dev --working-dir=$PWD --optimize-autoloader --no-interaction

echo "📁 Creating storage directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "🗑️ Clearing old cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "🧹 Removing cached files to ensure fresh environment..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-*.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/services.php

echo "🗄️ Running database migrations..."
php artisan migrate --force || echo "⚠️ Migration failed, continuing..."

echo "🎨 Caching configuration with fresh environment variables..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
