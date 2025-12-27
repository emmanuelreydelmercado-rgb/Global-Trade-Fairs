# Add CACHE_STORE to .env

$envFile = ".env"
$content = Get-Content $envFile -Raw

# Replace CACHE_DRIVER line with both CACHE_DRIVER and CACHE_STORE
$content = $content -replace "CACHE_DRIVER=redis", "CACHE_DRIVER=redis`nCACHE_STORE=redis"

# Save
$content | Set-Content $envFile -NoNewline

Write-Host "✅ Added CACHE_STORE=redis to .env" -ForegroundColor Green
