<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Tests\Helpers;

use Illuminate\Support\Facades\Event;
use Misaf\EmailWebhooks\Events\EmailBouncedEvent;
use Misaf\EmailWebhooks\Events\EmailComplainedEvent;
use Misaf\EmailWebhooks\Events\EmailFailedEvent;
use Misaf\EmailWebhooks\Events\EmailSentEvent;
use Misaf\EmailWebhooksResend\DataTransferObjects\ResendEventDto;

final class EventAssertionHelper
{
    /**
     * Assert that a sent event was dispatched with correct data
     *
     * @param array<string>|null $expectedRecipients
     * @return void
     */
    public static function assertSentEventDispatched(?array $expectedRecipients = null): void
    {
        Event::assertDispatched(EmailSentEvent::class, function (EmailSentEvent $event) use ($expectedRecipients) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEventDto) {
                return false;
            }

            if (null !== $expectedRecipients) {
                return $eventData->to === $expectedRecipients;
            }

            return true;
        });
    }

    /**
     * Assert that a bounced event was dispatched with correct data
     *
     * @param string|null $expectedType
     * @param string|null $expectedMessage
     * @param string|null $expectedSubType
     * @return void
     */
    public static function assertBouncedEventDispatched(
        ?string $expectedType = null,
        ?string $expectedMessage = null,
        ?string $expectedSubType = null,
    ): void {
        Event::assertDispatched(EmailBouncedEvent::class, function (EmailBouncedEvent $event) use ($expectedType, $expectedMessage, $expectedSubType) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEventDto) {
                return false;
            }

            $bounce = $eventData->bounce;
            if (null === $bounce) {
                return false;
            }

            if (null !== $expectedType && $bounce->type !== $expectedType) {
                return false;
            }

            if (null !== $expectedMessage && $bounce->message !== $expectedMessage) {
                return false;
            }

            return ! (null !== $expectedSubType && $bounce->subType !== $expectedSubType)



            ;
        });
    }

    /**
     * Assert that a complained event was dispatched with correct data
     *
     * @param string|null $expectedType
     * @param string|null $expectedMessage
     * @param string|null $expectedSubType
     * @return void
     */
    public static function assertComplainedEventDispatched(
        ?string $expectedType = null,
        ?string $expectedMessage = null,
        ?string $expectedSubType = null,
    ): void {
        Event::assertDispatched(EmailComplainedEvent::class, function (EmailComplainedEvent $event) use ($expectedType, $expectedMessage, $expectedSubType) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEventDto) {
                return false;
            }

            $bounce = $eventData->bounce;
            if (null === $bounce) {
                return false;
            }

            if (null !== $expectedType && $bounce->type !== $expectedType) {
                return false;
            }

            if (null !== $expectedMessage && $bounce->message !== $expectedMessage) {
                return false;
            }

            return ! (null !== $expectedSubType && $bounce->subType !== $expectedSubType)



            ;
        });
    }

    /**
     * Assert that a failed event was dispatched with correct data
     *
     * @return void
     */
    public static function assertFailedEventDispatched(): void
    {
        Event::assertDispatched(EmailFailedEvent::class, function (EmailFailedEvent $event) {
            $eventData = $event->eventData;
            return $eventData instanceof ResendEventDto;
        });
    }

    /**
     * Assert that no events were dispatched
     *
     * @return void
     */
    public static function assertNoEventsDispatched(): void
    {
        Event::assertNotDispatched(EmailSentEvent::class);
        Event::assertNotDispatched(EmailBouncedEvent::class);
        Event::assertNotDispatched(EmailComplainedEvent::class);
        Event::assertNotDispatched(EmailFailedEvent::class);
    }

    /**
     * Assert that a specific event was not dispatched
     *
     * @param string $eventClass
     * @return void
     */
    public static function assertEventNotDispatched(string $eventClass): void
    {
        Event::assertNotDispatched($eventClass);
    }

    /**
     * Assert that an event was dispatched with ResendEventDto
     *
     * @param string $eventClass
     * @return void
     */
    public static function assertEventWithResendDto(string $eventClass): void
    {
        Event::assertDispatched($eventClass, function ($event) {
            return $event->eventData instanceof ResendEventDto;
        });
    }
}
