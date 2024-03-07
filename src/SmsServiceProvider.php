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
        $this->mergeConfigFrom(__DIR__.'/config/sms.php', 'sms');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'SmsService');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            
            $this->loadMigrationsFrom(__DIR__.'/migrations');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'sms-migrations');

            $this->publishes([
                __DIR__.'/config/sms.php' => config_path('sms.php'),
            ], 'sms-config');
    
            $this->publishes([
            __DIR__.'/lang/fa/sms.php' => resource_path('lang/vendor/SmsService/sms.php'),
                ]);
        }
    }
 }
