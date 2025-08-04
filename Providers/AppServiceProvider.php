<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply PHP settings from environment variables
        if ($maxExecutionTime = env('MAX_EXECUTION_TIME')) {
            ini_set('max_execution_time', $maxExecutionTime);
        }
    }
}
