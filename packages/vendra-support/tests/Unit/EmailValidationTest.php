<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Misaf\VendraSupport\Rules\EmailValidation;

function validateEmail(string $email, bool $isProduction = false): array
{
    $failures = [];

    (new EmailValidation($isProduction))->validate(
        'email',
        $email,
        function (string $message) use (&$failures): void {
            $failures[] = $message;
        },
    );

    return $failures;
}

beforeEach(function (): void {
    config(['services.email.allowed_domains' => ['example.com']]);
});

it('rejects a non-string value', function (): void {
    expect(validateEmail(''))->not->toBeEmpty()
        ->and(validateEmail(''))->toBeArray();
});

it('rejects a disallowed domain', function (): void {
    expect(validateEmail('user@blocked.test'))->not->toBeEmpty();
});

it('passes an allowed domain outside production without calling the API', function (): void {
    Http::fake();

    expect(validateEmail('user@example.com'))->toBeEmpty();

    Http::assertNothingSent();
});

it('checks deliverability through the API in production', function (): void {
    config([
        'services.emailable.host'    => 'https://api.emailable.test/verify',
        'services.emailable.api_key' => 'test-key',
    ]);

    Http::fake([
        '*' => Http::response(['state' => 'undeliverable'], 200),
    ]);

    expect(validateEmail('user@example.com', isProduction: true))->not->toBeEmpty();
});

it('accepts a deliverable address in production', function (): void {
    config([
        'services.emailable.host'    => 'https://api.emailable.test/verify',
        'services.emailable.api_key' => 'test-key',
    ]);

    Http::fake([
        '*' => Http::response(['state' => 'deliverable'], 200),
    ]);

    expect(validateEmail('user@example.com', isProduction: true))->toBeEmpty();
});
