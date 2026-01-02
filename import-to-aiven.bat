@echo off
echo Importing database to Aiven MySQL...
echo.

REM Aiven MySQL connection details
set AIVEN_HOST=mysql-383cd7ab-emmanuelreydelmercado.h.aivencloud.com
set AIVEN_PORT=13763
set AIVEN_USER=avnadmin
set AIVEN_DB=defaultdb

REM Update this path if your XAMPP is installed elsewhere
set MYSQL_PATH=C:\xampp\mysql\bin

echo Please enter your Aiven MySQL password when prompted...
echo.

"%MYSQL_PATH%\mysql.exe" -h %AIVEN_HOST% -P %AIVEN_PORT% -u %AIVEN_USER% -p %AIVEN_DB% < dbtable_backup.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Database imported successfully to Aiven!
    echo.
) else (
    echo.
    echo Import failed. Please check:
    echo 1. The dbtable_backup.sql file exists
    echo 2. Your Aiven password is correct
    echo 3. Your internet connection is working
    echo.
)

pause
