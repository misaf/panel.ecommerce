<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Facades;

use Illuminate\Support\Facades\Facade;
use Misaf\EmailWebhooks\Services\EmailWebhookService;

/**
 * @method static EmailWebhookService processEvent(array<string, mixed> $payload)
 */
final class EmailWebhooks extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'email.webhooks';
    }
}
