<?php

declare(strict_types=1);

use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Support\TranslationLocales;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Tenancy\TenantSchema;

use function Pest\Laravel\mock;

beforeEach(function (): void {
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();

    $tenantResolver->shouldReceive('foreignKey')->andReturn(TenantSchema::DEFAULT_FOREIGN_KEY);

    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturn(1);

    app()->instance(TenantResolver::class, $tenantResolver);
});

it('returns active locales in display order', function (): void {
    $english = Language::query()->create(['locale' => 'en', 'position' => 2]);
    $german = Language::query()->create(['locale' => 'de', 'position' => 1]);
    Language::query()->create(['locale' => 'fa', 'active' => false, 'position' => 3]);

    Language::query()->whereKey($english->getKey())->update(['position' => 2]);
    Language::query()->whereKey($german->getKey())->update(['position' => 1]);

    expect(TranslationLocales::active())->toBe(['de', 'en']);
});

it('falls back when every installed language is inactive', function (): void {
    config()->set('app.fallback_locale', 'fa');

    Language::query()->create(['locale' => 'en', 'active' => false, 'position' => 1]);

    expect(TranslationLocales::active())->toBe(['fa']);
});

it('falls back to the application fallback locale when none are active', function (): void {
    config()->set('app.fallback_locale', 'fa');

    expect(TranslationLocales::active())->toBe(['fa']);
});

it('adds active locales while retaining stored inactive locale values', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);
    Language::query()->create(['locale' => 'de', 'position' => 2]);

    expect(TranslationLocales::merge([
        'en' => 'English override',
        'fa' => 'Persian override',
    ]))->toBe([
        'en' => 'English override',
        'de' => '',
        'fa' => 'Persian override',
    ]);
});
