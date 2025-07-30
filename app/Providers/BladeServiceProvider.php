<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // @role directive - check if user has a specific role
        Blade::directive('role', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->roles->pluck('role_name')->intersect([$roles])->count()): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // @admin directive - check if user has admin role
        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->roles->pluck('role_name')->contains('A')): ?>";
        });

        Blade::directive('endadmin', function () {
            return "<?php endif; ?>";
        });

        // @contributor directive - check if user has contributor role
        Blade::directive('contributor', function () {
            return "<?php if(auth()->check() && auth()->user()->roles->pluck('role_name')->contains('C')): ?>";
        });

        Blade::directive('endcontributor', function () {
            return "<?php endif; ?>";
        });
    }
}
