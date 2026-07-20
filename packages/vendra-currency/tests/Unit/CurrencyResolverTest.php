<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Models\Currency;
use Misaf\VendraSupport\Contracts\CurrencyResolver;
use Misaf\VendraSupport\Support\EloquentCurrencyResolver;

it('binds the shared currency resolver contract', function (): void {
    expect(app(CurrencyResolver::class))->toBeInstanceOf(EloquentCurrencyResolver::class);
});

it('provides active currency options through the shared resolver', function (): void {
    makeCurrentTestTenant();

    Currency::factory()->create([
        'name'       => 'US Dollar',
        'iso_code'   => 'USD',
        'is_default' => true,
        'position'   => 1,
        'status'     => true,
    ]);

    Currency::factory()->create([
        'name'       => 'Euro',
        'iso_code'   => 'EUR',
        'is_default' => false,
        'position'   => 2,
        'status'     => true,
    ]);

    Currency::factory()->create([
        'name'       => 'British Pound',
        'iso_code'   => 'GBP',
        'is_default' => false,
        'position'   => 3,
        'status'     => false,
    ]);

    $resolver = app(CurrencyResolver::class);

    expect($resolver->available())->toBeTrue()
        ->and($resolver->defaultCode())->toBe('USD')
        ->and($resolver->options())->toBe([
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
        ])
        ->and($resolver->activeCodes())->toBe(['USD', 'EUR']);
});
