@echo off
echo Creating sessions table for database-based sessions...
echo.

echo Step 1: Creating session migration...
php artisan session:table

echo.
echo Step 2: Running migration on Aiven database...
php artisan migrate --force

echo.
echo ========================================
echo Sessions table created successfully!
echo ========================================
echo.
echo Next: Add SESSION_DRIVER=database to Render environment variables
echo.
pause
