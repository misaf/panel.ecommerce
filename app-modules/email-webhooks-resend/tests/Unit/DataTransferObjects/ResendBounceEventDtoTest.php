<?php

declare(strict_types=1);

use Misaf\EmailWebhooksResend\Tests\Helpers\TestResendBounceEventDto;

describe('ResendBounceEventDto', function (): void {
    it('creates bounce event from valid data', function (): void {
        $bounce = new TestResendBounceEventDto(
            type: 'Permanent',
            message: 'Hard bounce occurred',
            subType: 'OnAccountSuppressionList',
        );

        expect($bounce->type)->toBe('Permanent');
        expect($bounce->message)->toBe('Hard bounce occurred');
        expect($bounce->subType)->toBe('OnAccountSuppressionList');
    });

    it('creates bounce event from array', function (): void {
        $data = [
            'type'    => 'Temporary',
            'message' => 'Temporary failure',
            'subType' => 'Suppressed',
        ];

        $bounce = TestResendBounceEventDto::fromArray($data);

        expect($bounce->type)->toBe('Temporary');
        expect($bounce->message)->toBe('Temporary failure');
        expect($bounce->subType)->toBe('Suppressed');
    });

    it('converts to array correctly', function (): void {
        $bounce = new TestResendBounceEventDto(
            type: 'Permanent',
            message: 'Test bounce message',
            subType: 'General',
        );

        $array = $bounce->toArray();

        expect($array)->toHaveKey('type');
        expect($array)->toHaveKey('message');
        expect($array)->toHaveKey('subType');
        expect($array['type'])->toBe('Permanent');
        expect($array['message'])->toBe('Test bounce message');
        expect($array['subType'])->toBe('General');
    });

    it('handles different bounce types', function (): void {
        $types = ['Permanent', 'Temporary'];
        $subTypes = ['General', 'Suppressed', 'MailboxFull', 'MessageTooLarge'];

        foreach ($types as $type) {
            foreach ($subTypes as $subType) {
                $bounce = new TestResendBounceEventDto(
                    type: $type,
                    message: "Test {$type} {$subType}",
                    subType: $subType,
                );

                expect($bounce->type)->toBe($type);
                expect($bounce->subType)->toBe($subType);
            }
        }
    });
});
