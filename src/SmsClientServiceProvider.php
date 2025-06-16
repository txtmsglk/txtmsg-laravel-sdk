<?php

namespace Txtmsg\SmsClient;

use Illuminate\Support\ServiceProvider;

class SmsClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/txtmsg.php', 'txtmsg');

        $this->app->singleton('txtmsg', function () {
            return new TxtmsgClient(config('txtmsg.api_key'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/txtmsg.php' => config_path('txtmsg.php'),
        ], 'config');
    }
}