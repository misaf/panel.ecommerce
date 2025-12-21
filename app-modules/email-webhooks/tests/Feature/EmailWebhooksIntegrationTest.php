<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\EmailWebhooks\Facades\EmailWebhooks;
use Misaf\EmailWebhooks\Services\EmailWebhooksService;
use Misaf\EmailWebhooks\Tests\Helpers\TestEmailEventDto;

beforeEach(function (): void {
    Event::fake();
});

describe('Email Webhook Integration', function (): void {
    it('can process email events through the complete workflow', function (): void {
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
        expect($service->getEventData())->toBe($eventData);
        expect($service->getPrimaryRecipient())->toBe('test@example.com');
    });

    it('can handle different email event types', function (): void {
        $events = [
            'email.sent'       => ['shouldBeBounce' => false],
            'email.bounced'    => ['shouldBeBounce' => true],
            'email.complained' => ['shouldBeBounce' => false],
        ];

        foreach ($events as $eventType => $expectations) {
            $data = ['type' => $eventType];

            if ('email.bounced' === $eventType) {
                $data['bounce'] = [
                    'type'    => 'Permanent',
                    'message' => 'Test bounce message',
                    'subType' => 'General',
                ];
            }

            $eventData = TestEmailEventDto::fromArray($data);
            $service = new EmailWebhooksService($eventData);

            // wasSuccessful() always returns true as per the service implementation
            expect($service->wasSuccessful())->toBeTrue();

            if ($expectations['shouldBeBounce']) {
                expect($service->isHardBounce())->toBeTrue();
            }
        }
    });

    it('can retrieve default driver configuration', function (): void {
        config(['services.email.webhooks.default_provider' => 'test-provider']);

        expect(EmailWebhooks::getDefaultDriver())->toBe('test-provider');
    });
});
