<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmailService;
use App\Services\SmsService;
use App\Services\ClientUrlService;
use App\Services\ClientNotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register notification services
        $this->app->singleton(ClientUrlService::class);
        $this->app->singleton(EmailService::class, function ($app) {
            return new EmailService($app->make(ClientUrlService::class));
        });
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService($app->make(ClientUrlService::class));
        });
        $this->app->singleton(ClientNotificationService::class, function ($app) {
            return new ClientNotificationService(
                $app->make(EmailService::class),
                $app->make(SmsService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
