#!/usr/bin/env bash

echo "🚀 Starting application..."

# Run migrations (only in production)
if [ "$APP_ENV" = "production" ]; then
    echo "📊 Running database migrations..."
    php artisan migrate --force --no-interaction || echo "⚠️ Migrations failed or already applied"
fi

# Start the server
echo "🌐 Starting Laravel server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
