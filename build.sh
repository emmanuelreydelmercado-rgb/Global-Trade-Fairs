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

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🎨 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
