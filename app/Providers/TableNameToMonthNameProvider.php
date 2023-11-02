<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class TableNameToMonthNameProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('TableNameToMonthName', function () {
            return new \App\Services\TableNameToMonthName();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
