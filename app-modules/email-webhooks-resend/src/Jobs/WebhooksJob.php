<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Jobs;

use InvalidArgumentException;
use Misaf\EmailWebhooks\Facades\EmailWebhooks;
use Misaf\EmailWebhooks\Services\EmailWebhookDriver;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

final class WebhooksJob extends SpatieProcessWebhookJob
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        if ( ! is_array($payload)) {
            throw new InvalidArgumentException('Webhook payload must be an array');
        }

        /** @var EmailWebhookDriver $service */
        $service = EmailWebhooks::driver('resend');

        $service->processEvent($payload);
    }
}
