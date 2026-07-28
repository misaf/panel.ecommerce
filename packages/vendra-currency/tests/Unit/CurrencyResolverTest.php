<?php

declare(strict_types=1);

use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraSupport\Capabilities\CurrencyIntegration;
use Misaf\VendraSupport\Contracts\CurrencyResolver;
use Misaf\VendraSupport\Contracts\TenantResolver;

use function Pest\Laravel\mock;

beforeEach(function (): void {
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturn(1);

    app()->instance(TenantResolver::class, $tenantResolver);
});

it('resolves the default currency code from the database', function (): void {
    CurrencyFactory::new()->code('EUR')->createOne(['position' => 1]);
    CurrencyFactory::new()->code('USD')->default()->createOne(['position' => 2]);

    expect(app(CurrencyResolver::class)->defaultCode())->toBe('USD');
});

it('resolves options and active codes from active currencies only', function (): void {
    CurrencyFactory::new()->code('USD')->default()->createOne(['position' => 1]);
    CurrencyFactory::new()->code('EUR')->createOne(['position' => 2]);
    CurrencyFactory::new()->code('GBP')->inactive()->createOne(['position' => 3]);

    $resolver = app(CurrencyResolver::class);

    expect($resolver->options())->toBe([
        'EUR' => 'Euro',
        'USD' => 'US Dollar',
    ])
        ->and($resolver->activeCodes())->toEqualCanonicalizing(['USD', 'EUR']);
});

it('integrates with the shared currency facade', function (): void {
    CurrencyFactory::new()->code('USD')->default()->createOne(['position' => 1]);

    expect(CurrencyIntegration::isAvailable())->toBeTrue()
        ->and(CurrencyIntegration::defaultCode())->toBe('USD');
});
