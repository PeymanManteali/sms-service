<?php

namespace SmsService;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__.'/migrations' => database_path('migrations'),
                __DIR__.'/config/sms.php' => config_path('sms.php'),
                __DIR__.'/lang/fa/sms.php' => resource_path('lang/fa/sms.php')
            ], 'laravel-assets');
        }
    }
 }
