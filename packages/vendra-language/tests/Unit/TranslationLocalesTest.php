<?php

declare(strict_types=1);

use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Support\TranslationLocales;
use Misaf\VendraSupport\Contracts\TenantResolver;

use function Pest\Laravel\mock;

beforeEach(function (): void {
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturn(1);

    app()->instance(TenantResolver::class, $tenantResolver);
});

it('returns enabled locales in display order', function (): void {
    $english = Language::query()->create(['locale' => 'en', 'position' => 2]);
    $german = Language::query()->create(['locale' => 'de', 'position' => 1]);
    Language::query()->create(['locale' => 'fa', 'status' => false, 'position' => 3]);

    Language::query()->whereKey($english->getKey())->update(['position' => 2]);
    Language::query()->whereKey($german->getKey())->update(['position' => 1]);

    expect(TranslationLocales::enabled())->toBe(['de', 'en']);
});

it('falls back when every installed language is disabled', function (): void {
    config()->set('app.fallback_locale', 'fa');

    Language::query()->create(['locale' => 'en', 'status' => false, 'position' => 1]);

    expect(TranslationLocales::enabled())->toBe(['fa']);
});

it('falls back to the application fallback locale when none are enabled', function (): void {
    config()->set('app.fallback_locale', 'fa');

    expect(TranslationLocales::enabled())->toBe(['fa']);
});

it('adds enabled locales while retaining stored disabled locale values', function (): void {
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
