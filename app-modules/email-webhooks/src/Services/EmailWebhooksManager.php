<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Manager;

final class EmailWebhooksManager extends Manager
{
    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        $driver = Config::string('services.email.webhooks.default_provider');

        return $driver;
    }
}
