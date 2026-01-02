@echo off
echo Listing all MySQL databases...
echo.

REM Update this path if your XAMPP is installed elsewhere
set MYSQL_PATH=C:\xampp\mysql\bin

"%MYSQL_PATH%\mysql.exe" -u root -e "SHOW DATABASES;"

echo.
pause
