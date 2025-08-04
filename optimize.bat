@echo off
echo Laravel Performance Optimization Script
echo =====================================
echo.

echo Step 1: Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo Step 2: Optimizing the application...
php artisan optimize

echo.
echo Step 3: Caching configuration and routes...
php artisan config:cache
php artisan route:cache

echo.
echo Step 4: Optimizing class autoloader...
composer dump-autoload --optimize

echo.
echo Step 5: Optimizing SQLite database...
php artisan db:optimize

echo.
echo Optimization complete!
echo.
echo Please restart your web server for changes to take effect.
pause
