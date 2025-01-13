<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Vite;

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
    public function boot()
{
    if (app()->environment('testing')) {
        app()->singleton(Vite::class, function () {
            return new class {
                public function __invoke()
                {
                    return '';
                }

                public function asset($asset)
                {
                    return $asset;
                }
            };
        });
    }
}
}
