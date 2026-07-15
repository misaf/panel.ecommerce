<?php

declare(strict_types=1);

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Support\TenantAwareness;

use function Pest\Laravel\mock;

it('keeps only one default language for the current tenant', function (): void {
    $currentTenantId = 1;
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturnUsing(function () use (&$currentTenantId): int {
        return $currentTenantId;
    });

    app()->instance(TenantResolver::class, $tenantResolver);

    $english = Language::query()->create([
        'locale'     => 'en',
        'is_default' => true,
        'position'   => 1,
    ]);

    $german = Language::query()->create([
        'locale'     => 'de',
        'is_default' => true,
        'position'   => 2,
    ]);

    $currentTenantId = 2;

    $persian = Language::query()->create([
        'locale'     => 'fa',
        'is_default' => true,
        'position'   => 1,
    ]);

    expect($persian->refresh()->is_default)->toBeTrue()
        ->and(Language::query()->where('is_default', true)->count())->toBe(1);

    $currentTenantId = 1;

    expect($english->refresh()->is_default)->toBeFalse()
        ->and($german->refresh()->is_default)->toBeTrue()
        ->and(Language::query()->where('is_default', true)->count())->toBe(1);
});

it('creates the fresh language schema expected by the model', function (): void {
    $columns = [
        'id',
        'locale',
        'is_default',
        'position',
        'created_at',
        'updated_at',
    ];

    if (TenantAwareness::enabled()) {
        $columns[] = 'tenant_id';
    }

    expect(Schema::hasColumns('languages', $columns))->toBeTrue()
        ->and(Schema::hasColumn('languages', 'iso_code'))->toBeFalse()
        ->and(Schema::hasColumn('languages', 'status'))->toBeFalse()
        ->and(Schema::hasColumn('languages', 'deleted_at'))->toBeFalse();
});

it('resolves language switch locales after the current tenant is available', function (): void {
    $switch = LanguageSwitch::make();
    $currentTenantId = 1;
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturnUsing(function () use (&$currentTenantId): int {
        return $currentTenantId;
    });

    app()->instance(TenantResolver::class, $tenantResolver);

    Language::query()->create([
        'locale'     => 'en',
        'is_default' => true,
        'position'   => 1,
    ]);

    Language::query()->create([
        'locale'     => 'de',
        'is_default' => false,
        'position'   => 2,
    ]);

    $currentTenantId = 2;

    Language::query()->create([
        'locale'     => 'fa',
        'is_default' => true,
        'position'   => 1,
    ]);

    $currentTenantId = 1;

    expect($switch->getLocales())->toBe(['en', 'de']);
});

it('uses the application fallback locale when no language is enabled', function (): void {
    config()->set('app.locale', 'de');
    config()->set('app.fallback_locale', 'fa');

    expect(LanguageSwitch::make()->getLocales())->toBe(['fa']);
});

it('rejects bulk updates that would create multiple default languages', function (): void {
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturn(1);

    app()->instance(TenantResolver::class, $tenantResolver);

    Language::query()->create([
        'locale'     => 'en',
        'is_default' => false,
        'position'   => 1,
    ]);

    Language::query()->create([
        'locale'     => 'de',
        'is_default' => false,
        'position'   => 2,
    ]);

    expect(fn(): int => Language::query()->update(['is_default' => true]))
        ->toThrow(QueryException::class);
});
