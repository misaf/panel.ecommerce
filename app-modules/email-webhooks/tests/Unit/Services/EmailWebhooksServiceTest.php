<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\EmailWebhooks\Services\EmailWebhooksService;
use Misaf\EmailWebhooks\Tests\Helpers\TestEmailEventDto;

beforeEach(function (): void {
    Event::fake();
});

describe('EmailWebhooksService', function (): void {
    it('correctly identifies hard bounces', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->isHardBounce())->toBeTrue();
        expect($service->isSoftBounce())->toBeFalse();
    });

    it('correctly identifies soft bounces', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Temporary',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->isHardBounce())->toBeFalse();
        expect($service->isSoftBounce())->toBeTrue();
    });

    it('correctly identifies complaints', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'type' => 'email.complained',
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->isComplaint())->toBeTrue();
    });

    it('returns primary recipient from multiple recipients', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'to'   => ['primary@example.com', 'cc@example.com'],
            'type' => 'email.sent',
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->getPrimaryRecipient())->toBe('primary@example.com');
    });

    it('returns event data correctly', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'type' => 'email.sent',
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->getEventData())->toBe($eventData);
    });

    it('indicates successful processing', function (): void {
        $eventData = TestEmailEventDto::fromArray([
            'type' => 'email.sent',
        ]);
        $service = new EmailWebhooksService($eventData);

        expect($service->wasSuccessful())->toBeTrue();
    });
});
