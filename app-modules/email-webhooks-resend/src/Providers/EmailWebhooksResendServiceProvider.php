<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Providers;

use Illuminate\Support\ServiceProvider;
use Misaf\EmailWebhooks\Facades\EmailWebhooks;
use Misaf\EmailWebhooksResend\Services\ResendEmailWebhooksDriver;

final class EmailWebhooksResendServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        EmailWebhooks::extend('resend', function ($app) {
            return $app->make(ResendEmailWebhooksDriver::class);
        });
    }
}
