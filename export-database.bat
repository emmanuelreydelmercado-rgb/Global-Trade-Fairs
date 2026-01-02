@echo off
echo Exporting local MySQL database...

REM Update this path if your XAMPP is installed elsewhere
set MYSQL_PATH=C:\xampp\mysql\bin

REM Export the database
"%MYSQL_PATH%\mysqldump.exe" -u root dbtable > dbtable_backup.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Database exported successfully to dbtable_backup.sql
    echo.
) else (
    echo.
    echo Export failed. Please check:
    echo 1. XAMPP MySQL is running
    echo 2. The MYSQL_PATH is correct
    echo 3. Database name is 'dbtable'
    echo.
)

pause
