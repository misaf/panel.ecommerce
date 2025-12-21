<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Misaf\EmailWebhooks\Services\EmailWebhooksManager;

final class EmailWebhooksServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('email.webhooks', fn($app) => new EmailWebhooksManager($app));
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            'email.webhooks',
        ];
    }
}
