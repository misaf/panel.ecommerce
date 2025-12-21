<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\EmailWebhooks\Facades\EmailWebhooks;
use Misaf\EmailWebhooksResend\Services\ResendEmailWebhooksDriver;
use Misaf\EmailWebhooksResend\Tests\Helpers\EventAssertionHelper;
use Misaf\EmailWebhooksResend\Tests\Helpers\ResendEventPayloadHelper;

beforeEach(function (): void {
    Event::fake();
});

describe('ResendEmailWebhooksDriver', function (): void {
    it('registers as resend driver', function (): void {
        expect(EmailWebhooks::driver('resend'))->toBeInstanceOf(ResendEmailWebhooksDriver::class);
    });

    it('processes sent events', function (): void {
        $payload = ResendEventPayloadHelper::createSentPayload();

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertSentEventDispatched();
    });

    it('processes failed events', function (): void {
        $payload = ResendEventPayloadHelper::createFailedPayload();

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertFailedEventDispatched();
    });
});
