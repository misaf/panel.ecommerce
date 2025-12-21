<?php

declare(strict_types=1);

use Misaf\EmailWebhooks\Tests\Helpers\TestBounceEventDto;

describe('BounceEventDto', function (): void {
    it('creates bounce event with all required fields', function (): void {
        $bounce = new TestBounceEventDto(
            type: 'Permanent',
            message: 'Hard bounce message',
            subType: 'OnAccountSuppressionList',
        );

        expect($bounce->type)->toBe('Permanent');
        expect($bounce->message)->toBe('Hard bounce message');
        expect($bounce->subType)->toBe('OnAccountSuppressionList');
    });

    it('creates bounce event from array', function (): void {
        $data = [
            'type'    => 'Temporary',
            'message' => 'Temporary failure',
            'subType' => 'Suppressed',
        ];

        $bounce = TestBounceEventDto::fromArray($data);

        expect($bounce->type)->toBe('Temporary');
        expect($bounce->message)->toBe('Temporary failure');
        expect($bounce->subType)->toBe('Suppressed');
    });

    it('converts to array correctly', function (): void {
        $bounce = new TestBounceEventDto(
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
                $bounce = new TestBounceEventDto(
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
