<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Services;

use InvalidArgumentException;
use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;
use Misaf\EmailWebhooks\Events\EmailBouncedEvent;
use Misaf\EmailWebhooks\Events\EmailComplainedEvent;
use Misaf\EmailWebhooks\Events\EmailFailedEvent;
use Misaf\EmailWebhooks\Events\EmailSentEvent;

abstract class EmailWebhooksDriver
{
    /**
     * @param array<string, mixed> $payload
     * @return EmailWebhooksService
     */
    public function processEvent(array $payload): EmailWebhooksService
    {
        $validatedPayload = $this->validatePayload($payload);
        $eventData = $this->createEventFromPayload($validatedPayload);
        $this->dispatchEvent($eventData);

        return new EmailWebhooksService($eventData);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed> Validated payload data
     */
    abstract protected function validatePayload(array $payload): array;

    /**
     * @param array<string, mixed> $payload
     * @return EmailEventDto
     */
    abstract protected function createEventFromPayload(array $payload): EmailEventDto;

    /**
     * @param EmailEventDto $eventData
     * @return void
     */
    private function dispatchEvent(EmailEventDto $eventData): void
    {
        match ($eventData->type) {
            'email.sent'       => event(new EmailSentEvent($eventData)),
            'email.bounced'    => event(new EmailBouncedEvent($eventData)),
            'email.complained' => event(new EmailComplainedEvent($eventData)),
            'email.failed'     => event(new EmailFailedEvent($eventData)),
            default            => throw new InvalidArgumentException("Unsupported email event type: {$eventData->type}"),
        };
    }

    /**
     * @return string
     */
    abstract protected function getProviderName(): string;
}
