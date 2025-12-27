# Update .env file for Redis configuration

$envFile = ".env"
$content = Get-Content $envFile -Raw

# Update CACHE_STORE to CACHE_DRIVER
$content = $content -replace "CACHE_STORE=database", "CACHE_DRIVER=redis"

# Update REDIS_CLIENT
$content = $content -replace "REDIS_CLIENT=phpredis", "REDIS_CLIENT=predis"

# Save the file
$content | Set-Content $envFile -NoNewline

Write-Host "✅ .env file updated successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Changes made:" -ForegroundColor Yellow
Write-Host "  - CACHE_STORE=database → CACHE_DRIVER=redis" -ForegroundColor Cyan
Write-Host "  - REDIS_CLIENT=phpredis → REDIS_CLIENT=predis" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next step: Run 'php artisan config:clear'" -ForegroundColor Yellow
