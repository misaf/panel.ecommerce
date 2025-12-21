<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\EmailWebhooks\Facades\EmailWebhooks;

beforeEach(function (): void {
    Event::fake();
});

describe('EmailWebhookManager', function (): void {
    it('throws exception when no default driver is configured', function (): void {
        config(['services.email.webhooks.default_provider' => null]);

        expect(fn() => EmailWebhooks::getDefaultDriver())
            ->toThrow(
                InvalidArgumentException::class,
                'Please set services.email.webhooks.default_provider in your config.',
            );
    });

    it('returns configured default driver', function (): void {
        config(['services.email.webhooks.default_provider' => 'test-provider']);

        expect(EmailWebhooks::getDefaultDriver())->toBe('test-provider');
    });

    it('throws exception for unsupported driver', function (): void {
        expect(fn() => EmailWebhooks::driver('unsupported'))
            ->toThrow(
                InvalidArgumentException::class,
                'Driver [unsupported] not supported.',
            );
    });
});
