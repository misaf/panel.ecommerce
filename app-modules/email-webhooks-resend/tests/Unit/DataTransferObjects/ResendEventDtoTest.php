<?php

declare(strict_types=1);

use Misaf\EmailWebhooksResend\Tests\Helpers\TestResendEmailEventDto;

describe('ResendEventDto', function (): void {
    it('handles bounce data correctly', function (): void {
        $eventData = TestResendEmailEventDto::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);

        expect($eventData->bounce)->not->toBeNull();
        $bounce = $eventData->bounce;
        assert(null !== $bounce);
        expect($bounce->type)->toBe('Permanent');
        expect($bounce->message)->toBe('Test bounce message');
        expect($bounce->subType)->toBe('General');
    });

    it('handles events without bounce data', function (): void {
        $eventData = TestResendEmailEventDto::fromArray([
            'type' => 'email.sent',
        ]);

        expect($eventData->bounce)->toBeNull();
    });

    it('converts to array correctly', function (): void {
        $eventData = TestResendEmailEventDto::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);
        $array = $eventData->toArray();

        expect($array)->toHaveKey('to');
        expect($array['to'])->toBe(['test@example.com']);

        expect($array)->toHaveKey('from');
        expect($array['from'])->toBe('sender@example.com');

        expect($array)->toHaveKey('subject');
        expect($array['subject'])->toBe('Test Email');

        expect($array)->toHaveKey('email_id');
        expect($array['email_id'])->toBe('test-email-123');

        expect($array)->toHaveKey('created_at');
        expect($array['created_at'])->toBe('2024-01-01T12:00:00Z');

        expect($array)->toHaveKey('type');
        expect($array['type'])->toBe('email.bounced');

        expect($array)->toHaveKey('provider');
        expect($array['provider'])->toBe('test-provider');

        expect($array)->toHaveKey('bounce');
        /** @var array{type: string, message: string, subType: string} $bounceArray */
        $bounceArray = $array['bounce'] ?? [];
        expect($bounceArray)->toBeArray();
        expect($bounceArray)->toHaveKey('type');
        expect($bounceArray)->toHaveKey('message');
        expect($bounceArray)->toHaveKey('subType');
        expect($bounceArray['type'])->toBe('Permanent');
        expect($bounceArray['message'])->toBe('Test bounce message');
        expect($bounceArray['subType'])->toBe('General');
    });

    it('handles multiple recipients correctly', function (): void {
        $recipients = ['primary@example.com', 'cc@example.com', 'bcc@example.com'];
        $eventData = TestResendEmailEventDto::fromArray([
            'to'   => $recipients,
            'type' => 'email.sent',
        ]);

        expect($eventData->to)->toHaveCount(3);
        expect($eventData->to)->toBe($recipients);
    });

    it('stores original payload correctly', function (): void {
        $eventData = TestResendEmailEventDto::fromArray([
            'original_payload' => ['test' => 'data'],
            'type'             => 'email.sent',
        ]);

        expect($eventData->originalPayload)->toHaveKey('test');
        expect($eventData->originalPayload['test'])->toBe('data');
    });
});
