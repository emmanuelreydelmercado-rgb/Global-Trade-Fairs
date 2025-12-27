<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Redis Connection...\n\n";

try {
    // Test 1: Get cache driver
    $driver = config('cache.default');
    echo "✅ Cache Driver: " . $driver . "\n";
    
    // Test 2: Put and get from cache
    \Illuminate\Support\Facades\Cache::put('test-key', 'Redis is working! 🚀', 60);
    $value = \Illuminate\Support\Facades\Cache::get('test-key');
    echo "✅ Cache Test: " . $value . "\n";
    
    // Test 3: Direct Redis connection
    \Illuminate\Support\Facades\Redis::set('direct-test', 'Direct Redis works!');
    $direct = \Illuminate\Support\Facades\Redis::get('direct-test');
    echo "✅ Direct Redis: " . $direct . "\n";
    
    // Test 4: Performance test
    $start = microtime(true);
    for ($i = 0; $i < 100; $i++) {
        \Illuminate\Support\Facades\Cache::put("perf-$i", "value-$i", 60);
        \Illuminate\Support\Facades\Cache::get("perf-$i");
    }
    $time = round((microtime(true) - $start) * 1000, 2);
    echo "✅ Performance: " . $time . "ms for 100 operations\n";
    
    echo "\n🎉 SUCCESS! Redis is working perfectly!\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Check Memurai is running: Get-Service Memurai\n";
    echo "2. Check .env has CACHE_DRIVER=redis\n";
    echo "3. Check .env has REDIS_CLIENT=predis\n";
    exit(1);
}
