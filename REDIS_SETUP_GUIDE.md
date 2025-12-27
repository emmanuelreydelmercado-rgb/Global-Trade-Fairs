# 🔴 Redis Setup Guide for Windows (XAMPP Users)

## ✅ Recommended: Memurai (Redis for Windows)

Since you're using XAMPP on Windows, **Memurai** is the best choice - it's Redis built specifically for Windows.

---

## 📥 **Step 1: Download Memurai**

### **Option A: Direct Download (Recommended)**

1. **Visit**: https://www.memurai.com/get-memurai
2. **Click**: "Download Memurai Developer" (Free version)
3. **File**: `Memurai-Developer-v4.x.x.msi` (about 15MB)

### **Option B: Using Chocolatey** (if you have it)

```powershell
choco install memurai-developer
```

---

## 🛠️ **Step 2: Install Memurai**

1. **Run** the downloaded `.msi` file
2. **Click** "Next" → "I agree" → "Next" → "Install"
3. **Wait** for installation (takes ~30 seconds)
4. **Finish** - Memurai will start automatically as a Windows Service

---

## ✅ **Step 3: Verify Installation**

Open PowerShell and run:

```powershell
# Check if Memurai service is running
Get-Service Memurai

# Should show:
# Status   Name      DisplayName
# ------   ----      -----------
# Running  Memurai   Memurai
```

### **Test Redis Connection**

```powershell
# Connect to Memurai CLI
memurai-cli

# Inside Memurai CLI, type:
PING
# Should return: PONG

# Exit
exit
```

---

## 📦 **Step 4: Install PHP Redis Extension**

### **For XAMPP Users (Like You)**

#### **Method 1: Using PECL (Recommended)**

```powershell
# Navigate to XAMPP PHP directory
cd C:\xampp-64\php

# Install Redis extension
pecl install redis
```

If PECL doesn't work, use Method 2:

#### **Method 2: Manual Installation**

1. **Download PHP Redis DLL**:
   - Visit: https://pecl.php.net/package/redis
   - Click on "DLL" for your PHP version (8.2)
   - Download: `php_redis-x.x.x-8.2-ts-vs16-x64.zip`

2. **Extract** the `php_redis.dll` file

3. **Copy** to XAMPP extensions folder:
   ```
   C:\xampp-64\php\ext\php_redis.dll
   ```

4. **Edit** `php.ini` (located at `C:\xampp-64\php\php.ini`):
   ```ini
   # Add this line at the end of the extensions section:
   extension=redis
   ```

5. **Restart** Apache from XAMPP Control Panel

6. **Verify**:
   ```powershell
   php -m | findstr redis
   # Should show: redis
   ```

---

## 🔧 **Step 5: Install Laravel Predis Package**

```powershell
cd C:\Users\LENOVO\Desktop\manuel\sample1
composer require predis/predis
```

---

## ⚙️ **Step 6: Configure Laravel**

### **Edit `.env` file**

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### **Clear Laravel Cache**

```powershell
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 **Step 7: Test Redis in Laravel**

### **Option A: Using Tinker**

```powershell
php artisan tinker
```

Then run:
```php
Cache::put('test', 'Hello from Redis!', 60);
Cache::get('test');
// Should return: "Hello from Redis!"

Redis::ping();
// Should return: "+PONG"

exit
```

### **Option B: Create a Test Route**

Create a test file: `routes/web.php`

```php
Route::get('/test-redis', function() {
    // Test 1: Cache
    Cache::put('test-key', 'Redis is working!', 600);
    $cached = Cache::get('test-key');
    
    // Test 2: Direct Redis
    Redis::set('direct-test', 'Direct Redis works!');
    $direct = Redis::get('direct-test');
    
    return response()->json([
        'status' => 'success',
        'cache_test' => $cached,
        'redis_test' => $direct,
        'message' => 'Redis is configured correctly!'
    ]);
});
```

Visit: `http://localhost/test-redis`

---

## 🎯 **Step 8: Monitoring Redis**

### **Memurai CLI Commands**

```powershell
# Connect
memurai-cli

# Check memory usage
INFO memory

# See all keys
KEYS *

# Get value of a key
GET test-key

# See statistics
INFO stats

# Monitor real-time commands
MONITOR

# Clear all data (careful!)
FLUSHALL
```

---

## 🚨 **Troubleshooting**

### **Issue: Memurai service not running**

```powershell
# Start service
Start-Service Memurai

# Check status
Get-Service Memurai
```

### **Issue: Connection refused**

1. Check Memurai is running
2. Check port 6379 is not blocked by firewall
3. Verify `REDIS_HOST=127.0.0.1` in `.env`

### **Issue: PHP redis extension not loading**

```powershell
# Check if extension exists
php -m | findstr redis

# If not found, verify:
# 1. php_redis.dll is in C:\xampp-64\php\ext\
# 2. extension=redis is in php.ini
# 3. Apache was restarted
```

---

## 🎉 **Alternative: Redis via Docker** (If you have Docker)

If you prefer Docker:

```powershell
# Pull Redis image
docker pull redis:latest

# Run Redis container
docker run -d --name redis-container -p 6379:6379 redis:latest

# Test
docker exec -it redis-container redis-cli ping
```

Then just configure Laravel `.env` as above.

---

## 📊 **Performance Testing**

### **Benchmark File Cache vs Redis**

```php
// Test in routes/web.php
Route::get('/benchmark-cache', function() {
    $iterations = 1000;
    
    // Test 1: File Cache
    $fileStart = microtime(true);
    config(['cache.default' => 'file']);
    for ($i = 0; $i < $iterations; $i++) {
        Cache::put("file-test-$i", "value-$i", 60);
        Cache::get("file-test-$i");
    }
    $fileTime = (microtime(true) - $fileStart) * 1000;
    
    // Test 2: Redis
    $redisStart = microtime(true);
    config(['cache.default' => 'redis']);
    for ($i = 0; $i < $iterations; $i++) {
        Cache::put("redis-test-$i", "value-$i", 60);
        Cache::get("redis-test-$i");
    }
    $redisTime = (microtime(true) - $redisStart) * 1000;
    
    return response()->json([
        'iterations' => $iterations,
        'file_cache' => round($fileTime, 2) . 'ms',
        'redis_cache' => round($redisTime, 2) . 'ms',
        'improvement' => round(($fileTime / $redisTime), 2) . 'x faster'
    ]);
});
```

---

## 📚 **Next Steps**

Once Redis is working:

1. ✅ Implement caching in controllers
2. ✅ Use Redis for sessions (faster login/logout)
3. ✅ Use Redis for queues (background jobs)
4. ✅ Monitor performance improvements

---

## 🔗 **Useful Resources**

- **Memurai Docs**: https://docs.memurai.com/
- **Laravel Redis**: https://laravel.com/docs/redis
- **Redis Commands**: https://redis.io/commands/
- **Predis GitHub**: https://github.com/predis/predis

---

## ⚡ **Quick Reference**

### **Start/Stop Redis**
```powershell
Start-Service Memurai   # Start
Stop-Service Memurai    # Stop
Restart-Service Memurai # Restart
```

### **Laravel Cache Commands**
```powershell
php artisan cache:clear    # Clear cache
php artisan config:clear   # Clear config
php artisan config:cache   # Cache config
```

### **Tinker Quick Tests**
```php
php artisan tinker
Cache::put('test', 'value', 60);
Cache::get('test');
Redis::ping();
exit
```

---

**You're ready to use Redis! 🚀**
