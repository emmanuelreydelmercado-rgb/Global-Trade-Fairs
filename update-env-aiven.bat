@echo off
echo ========================================
echo   Updating .env for Aiven MySQL
echo ========================================
echo.
echo This will update your .env file to use Aiven cloud database.
echo.
echo IMPORTANT: Your Aiven password will be needed!
echo.
set /p AIVEN_PASSWORD="Enter your Aiven MySQL password: "
echo.

REM Backup current .env
copy .env .env.backup.local 2>nul
if %ERRORLEVEL% EQU 0 (
    echo ✓ Backed up current .env to .env.backup.local
) else (
    echo ! Could not backup .env file
)

REM Create temporary PowerShell script to update .env
echo $envContent = Get-Content .env > update-env.ps1
echo $envContent = $envContent -replace '^DB_CONNECTION=.*', 'DB_CONNECTION=mysql' >> update-env.ps1
echo $envContent = $envContent -replace '^DB_HOST=.*', 'DB_HOST=mysql-383cd7ab-emmanuelreydelmercado.h.aivencloud.com' >> update-env.ps1
echo $envContent = $envContent -replace '^DB_PORT=.*', 'DB_PORT=13763' >> update-env.ps1
echo $envContent = $envContent -replace '^DB_DATABASE=.*', 'DB_DATABASE=defaultdb' >> update-env.ps1
echo $envContent = $envContent -replace '^DB_USERNAME=.*', 'DB_USERNAME=avnadmin' >> update-env.ps1
echo $envContent = $envContent -replace '^DB_PASSWORD=.*', 'DB_PASSWORD=%AIVEN_PASSWORD%' >> update-env.ps1
echo $envContent ^| Set-Content .env >> update-env.ps1

REM Execute PowerShell script
powershell -ExecutionPolicy Bypass -File update-env.ps1

REM Clean up
del update-env.ps1

echo.
echo ✓ .env file updated with Aiven MySQL credentials
echo.
echo ========================================
echo   Clearing Laravel Cache
echo ========================================
echo.

php artisan config:clear
php artisan cache:clear

echo.
echo ========================================
echo   DONE!
echo ========================================
echo.
echo Your app is now configured to use Aiven Cloud MySQL
echo.
echo Next steps:
echo 1. Restart 'php artisan serve' if it's running
echo 2. Test your app in the browser
echo 3. Update Render environment variables
echo.
pause
