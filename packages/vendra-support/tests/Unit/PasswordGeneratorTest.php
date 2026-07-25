<?php

declare(strict_types=1);

use Misaf\VendraSupport\Support\PasswordGenerator;

it('generates a password of the default length', function (): void {
    expect(PasswordGenerator::generate())->toHaveLength(8);
});

it('generates a password of the requested length', function (): void {
    expect(PasswordGenerator::generate(10))->toHaveLength(10);
});

it('only uses the allowed character set', function (): void {
    expect(PasswordGenerator::generate(64))->toMatch('/^[1-9a-z]+$/');
});

it('produces different passwords on each call', function (): void {
    expect(PasswordGenerator::generate(12))->not->toBe(PasswordGenerator::generate(12));
});
