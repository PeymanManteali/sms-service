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
        if ($this->app->runningInConsole()) {
            // انتشار فایل‌های migration
            $this->publishes([
                __DIR__.'/migrations' => database_path('migrations'),
            ], 'migrations');
        
            // انتشار فایل‌های config
            $this->publishes([
                __DIR__.'/config/sms.php' => config_path('sms.php'),
            ], 'config');
        
            // انتشار فایل‌های زبان
            $this->loadTranslationsFrom(__DIR__.'/lang', 'SmsService');
            $this->publishes([
                __DIR__.'/lang' => resource_path('lang/vendor/SmsService'),
            ]);
        }
    }
}
