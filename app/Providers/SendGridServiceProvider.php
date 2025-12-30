<?php

namespace App\Providers;

use App\Mail\SendGridApiTransport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class SendGridServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('sendgrid_api', function (array $config) {
            return new SendGridApiTransport($config['api_key']);
        });
    }
}
