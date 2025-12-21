<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;

abstract class BaseEmailEvent
{
    use Dispatchable;

    /**
     * @param EmailEventDto $eventData
     */
    public function __construct(
        public readonly EmailEventDto $eventData,
    ) {}
}
