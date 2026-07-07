<?php

namespace Txtmsg\SmsClient;

use Illuminate\Support\ServiceProvider;

class SmsClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/txtmsg.php', 'txtmsg');

        $this->app->singleton('txtmsg', function (): TxtmsgClient {
            return new TxtmsgClient(
                apiKey: config('txtmsg.api_key'),
                baseUrl: config('txtmsg.base_url'),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/txtmsg.php' => config_path('txtmsg.php'),
            ], 'txtmsg-config');
        }
    }
}
