<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Services;

use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;

final class EmailWebhooksService
{
    /**
     * @param EmailEventDto $eventData
     */
    public function __construct(
        private readonly EmailEventDto $eventData,
    ) {}

    /**
     * Get the underlying event data
     *
     * @return EmailEventDto
     */
    public function getEventData(): EmailEventDto
    {
        return $this->eventData;
    }

    /**
     * Check if the email event represents a hard bounce
     *
     * @return bool
     */
    public function isHardBounce(): bool
    {
        return 'Permanent' === $this->eventData->bounce?->type;
    }

    /**
     * Check if the email event represents a soft bounce
     *
     * @return bool
     */
    public function isSoftBounce(): bool
    {
        return 'Temporary' === $this->eventData->bounce?->type;
    }

    /**
     * Check if the email event represents a complaint
     *
     * @return bool
     */
    public function isComplaint(): bool
    {
        return 'email.complained' === $this->eventData->type;
    }

    /**
     * Get the primary recipient email from the event
     *
     * @return string
     */
    public function getPrimaryRecipient(): string
    {
        return $this->eventData->to[0];
    }

    /**
     * Check if the event was successfully processed
     *
     * Note: This method currently always returns true as the service
     * only processes events that have already been validated and created.
     * In a real implementation, this might check for processing status.
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return true;
    }
}
