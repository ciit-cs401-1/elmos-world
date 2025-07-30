# Performance Optimization Guide

This document provides instructions for optimizing the performance of this Laravel application.

## Quick Optimization

Run the included optimization script to apply all performance improvements:

```bash
./optimize.bat  # Windows
# OR
sh optimize.sh  # Linux/Mac
```

## Manual Optimization Steps

### 1. Database Optimization

The application uses SQLite, which has been configured for optimal performance. To re-apply these optimizations:

```bash
php artisan db:optimize
```

This sets the following SQLite pragmas:
- `journal_mode = WAL` (Write-Ahead Logging)
- `synchronous = NORMAL`
- `cache_size = 10000`
- `temp_store = MEMORY`
- `mmap_size` for better memory mapping

### 2. Laravel Framework Optimization

Clear all caches and regenerate optimized versions:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
php artisan config:cache
php artisan route:cache
composer dump-autoload --optimize
```

### 3. Performance Monitoring

A performance monitoring middleware has been added that:
- Logs slow requests (over 500ms) to the Laravel log
- Adds an X-Execution-Time header to responses

### 4. Session and Cache Configuration

For optimal performance, ensure these settings in your .env file:

```
CACHE_DRIVER=file
SESSION_DRIVER=file
TELESCOPE_ENABLED=false
```

## Troubleshooting Slow Requests

If the application becomes slow again:

1. Check the Laravel logs for slow request warnings
2. Run the optimization script to refresh all performance settings
3. Verify that caching is properly configured in `.env`
4. Check for N+1 query issues in controllers
5. Review the PerformanceMonitor middleware logs for slow requests

## Environment Variables

Important performance-related environment variables:

```
# Performance settings
CACHE_DRIVER=file      # Use 'redis' for even better performance in production
SESSION_DRIVER=file    # Use 'redis' for even better performance in production
TELESCOPE_ENABLED=false
MAX_EXECUTION_TIME=300
```

## Included Performance Tools

The project includes these performance optimization tools:

1. **OptimizeSqlite Command** (`php artisan db:optimize`): Optimizes the SQLite database settings
2. **PerformanceMonitor Middleware**: Monitors and logs slow requests
3. **optimize.bat**: Windows batch script for quick application optimization
4. **AppServiceProvider Settings**: Applies PHP settings from environment variables
