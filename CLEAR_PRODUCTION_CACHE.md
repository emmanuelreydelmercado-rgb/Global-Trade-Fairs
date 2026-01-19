# Clear Production Cache - Fix Email Verification URLs

## Problem
Email verification links are using `127.0.0.1` even though `APP_URL` is set correctly in Render.

## Root Cause
The configuration was **cached** during a previous build when `APP_URL` may have been set to localhost. Laravel is using the cached config instead of reading the current environment variables.

## Solution

### Option 1: Clear Cache via Render Shell (QUICK FIX)

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Select your **Global-Trade-Fairs** web service
3. Click **Shell** in the left sidebar
4. Run these commands one by one:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

5. Then rebuild the cache with the correct URL:

```bash
php artisan config:cache
php artisan route:cache
```

6. **Test immediately** - Register a new user and check the email

### Option 2: Trigger a Manual Deploy (RECOMMENDED)

This ensures everything is rebuilt from scratch with the correct environment variables:

1. Go to your Render Dashboard
2. Click **Manual Deploy** → **Deploy latest commit**
3. This will run `build.sh` which will:
   - Clear all caches
   - Rebuild caches with the current `APP_URL` from environment variables

### Option 3: Add Cache Clear to Build Script

Update `build.sh` to ensure cache is always cleared before building:

```bash
# Add this BEFORE the caching section
echo "🧹 Ensuring old cache is completely cleared..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear  
php artisan view:clear

# Remove any cached config files
rm -rf bootstrap/cache/config.php
rm -rf bootstrap/cache/routes-*.php

# Then cache with fresh environment
echo "🎨 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Verify the Fix

After clearing cache, test with these steps:

1. Register a new test user
2. Check the verification email
3. Look at the URL in the "Verify Email Address" button
4. It should show: `https://global-trade-fairs.onrender.com/verify/...`
5. Click it and verify it works

## Why Config Cache is Tricky

When you run `php artisan config:cache`:
- Laravel reads ALL environment variables
- Stores them in `bootstrap/cache/config.php`
- From that point on, `env()` calls return `null`
- It ONLY uses the cached values

If the wrong `APP_URL` was set when you cached, it stays wrong until you clear and re-cache.

## Prevention

Always ensure:
1. Environment variables are set BEFORE deploying
2. Never manually cache config with wrong env vars
3. Use Manual Deploy to rebuild everything correctly
