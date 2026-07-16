<?php

declare(strict_types=1);

use Misaf\VendraSupport\Support\Countries;

it('provides official ISO countries with localized names', function (): void {
    $englishCountries = Countries::options('en');
    $persianCountries = Countries::options('fa');

    expect($englishCountries)
        ->toHaveCount(249)
        ->not->toHaveKeys(['EU', 'UN', 'XK'])
        ->and(Countries::codes())->toHaveCount(249)->toContain('IR')
        ->and($englishCountries['IR'])->toBe('Iran')
        ->and($persianCountries['IR'])->toBe('ایران');
});

it('uses the application locale by default', function (): void {
    app()->setLocale('fa');

    expect(Countries::options()['IR'])->toBe('ایران')
        ->and(Countries::name('IR'))->toBe('ایران');
});
