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

describe('Resend Webhook Integration', function (): void {
    it('processes sent events correctly', function (): void {
        $payload = ResendEventPayloadHelper::createSentPayload();

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertSentEventDispatched();
    });

    it('processes bounce events correctly', function (): void {
        $payload = ResendEventPayloadHelper::createBouncedPayload();

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertBouncedEventDispatched();
    });

    it('processes failed events correctly', function (): void {
        $payload = ResendEventPayloadHelper::createFailedPayload();

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertFailedEventDispatched();
    });

    it('handles multiple recipients', function (): void {
        $recipients = ['primary@google.com', 'cc@google.com', 'bcc@google.com'];
        $payload = ResendEventPayloadHelper::createMultiRecipientPayload('email.sent', $recipients);

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $service->processEvent($payload);

        EventAssertionHelper::assertSentEventDispatched($recipients);
    });

    it('analyzes hard bounces correctly', function (): void {
        $payload = ResendEventPayloadHelper::createBouncedPayload(
            'Permanent',
            'Hard bounce',
            'OnAccountSuppressionList',
        );

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $analysis = $service->processEvent($payload);

        expect($analysis->isHardBounce())->toBeTrue();
        expect($analysis->isSoftBounce())->toBeFalse();
    });

    it('analyzes soft bounces correctly', function (): void {
        $payload = ResendEventPayloadHelper::createBouncedPayload(
            'Temporary',
            'Soft bounce',
            'General',
        );

        /** @var ResendEmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');
        $analysis = $service->processEvent($payload);

        expect($analysis->isHardBounce())->toBeFalse();
        expect($analysis->isSoftBounce())->toBeTrue();
    });
});
