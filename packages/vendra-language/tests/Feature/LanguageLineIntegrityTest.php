<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\Translator;
use Misaf\VendraLanguage\Localization\NamespacedTranslationLoaderManager;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraSupport\Contracts\TenantResolver;

use function Pest\Laravel\mock;

beforeEach(function (): void {
    $this->currentTenantId = 1;
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturnUsing(
        fn(): int => $this->currentTenantId,
    );

    app()->instance(TenantResolver::class, $tenantResolver);
});

it('allows the same translation key in different groups but rejects exact duplicates', function (): void {
    LanguageLine::query()->create([
        'group' => 'authentication',
        'key'   => 'email',
        'text'  => ['en' => 'Email'],
    ]);

    LanguageLine::query()->create([
        'group' => 'validation',
        'key'   => 'email',
        'text'  => ['en' => 'The email field is required.'],
    ]);

    expect(fn(): LanguageLine => LanguageLine::query()->create([
        'group' => 'authentication',
        'key'   => 'email',
        'text'  => ['en' => 'Email address'],
    ]))->toThrow(QueryException::class);
});

it('scopes translation uniqueness by namespace', function (): void {
    LanguageLine::query()->create([
        'namespace' => 'vendra-product',
        'group'     => 'attributes',
        'key'       => 'name',
        'text'      => ['en' => 'Product name'],
    ]);

    LanguageLine::query()->create([
        'namespace' => 'vendra-language',
        'group'     => 'attributes',
        'key'       => 'name',
        'text'      => ['en' => 'Language name'],
    ]);

    expect(fn(): LanguageLine => LanguageLine::query()->create([
        'namespace' => 'vendra-product',
        'group'     => 'attributes',
        'key'       => 'name',
        'text'      => ['en' => 'Duplicate product name'],
    ]))->toThrow(QueryException::class);
});

it('still rejects duplicate application translations with a null namespace', function (): void {
    LanguageLine::query()->create([
        'namespace' => null,
        'group'     => 'validation',
        'key'       => 'required',
        'text'      => ['en' => 'Required'],
    ]);

    expect(fn(): LanguageLine => LanguageLine::query()->create([
        'namespace' => null,
        'group'     => 'validation',
        'key'       => 'required',
        'text'      => ['en' => 'This field is required'],
    ]))->toThrow(QueryException::class);
});

it('invalidates cache entries for the original group and removed locales', function (): void {
    $languageLine = LanguageLine::query()->create([
        'group' => 'navigation',
        'key'   => 'dashboard',
        'text'  => [
            'en' => 'Dashboard',
            'de' => 'Instrumententafel',
        ],
    ]);

    Cache::put(LanguageLine::getCacheKey('navigation', 'en'), ['dashboard' => 'Dashboard']);
    Cache::put(LanguageLine::getCacheKey('navigation', 'de'), ['dashboard' => 'Instrumententafel']);

    $languageLine->update([
        'group' => 'modules',
        'text'  => ['en' => 'Dashboard'],
    ]);

    expect(Cache::has(LanguageLine::getCacheKey('navigation', 'en')))->toBeFalse()
        ->and(Cache::has(LanguageLine::getCacheKey('navigation', 'de')))->toBeFalse();
});

it('uses tenant-qualified translation cache keys', function (): void {
    $tenantOneKey = LanguageLine::getCacheKey('navigation', 'en');

    $this->currentTenantId = 2;

    expect(LanguageLine::getCacheKey('navigation', 'en'))
        ->not->toBe($tenantOneKey);
});

it('uses namespace-qualified translation cache keys', function (): void {
    expect(LanguageLine::getCacheKey('attributes', 'en', 'vendra-product'))
        ->not->toBe(LanguageLine::getCacheKey('attributes', 'en', 'vendra-language'))
        ->and(LanguageLine::getCacheKey('attributes', 'en', 'vendra-product'))
        ->not->toBe(LanguageLine::getCacheKey('attributes', 'en'));
});

it('invalidates the original namespace cache when a line moves', function (): void {
    $languageLine = LanguageLine::query()->create([
        'namespace' => 'vendra-product',
        'group'     => 'attributes',
        'key'       => 'name',
        'text'      => ['en' => 'Product name'],
    ]);

    $cacheKey = LanguageLine::getCacheKey('attributes', 'en', 'vendra-product');
    Cache::put($cacheKey, ['name' => 'Product name']);

    $languageLine->update(['namespace' => 'vendra-language']);

    expect(Cache::has($cacheKey))->toBeFalse();
});

it('overrides package translations from the database without losing file translations', function (): void {
    LanguageLine::query()->create([
        'namespace' => 'vendra-language',
        'group'     => 'navigation',
        'key'       => 'language',
        'text'      => ['en' => 'Tenant Languages'],
    ]);

    $translator = app('translator');

    expect($translator)->toBeInstanceOf(Translator::class);

    $translator->setLocale('en');
    $translator->setLoaded([]);

    expect(__('vendra-language::navigation.language'))->toBe('Tenant Languages')
        ->and(__('vendra-language::navigation.languages'))->toBe('Languages');
});

it('registers the namespaced translation loader and language line model', function (): void {
    expect(config('translation-loader.model'))->toBe(LanguageLine::class)
        ->and(config('translation-loader.translation_manager'))->toBe(NamespacedTranslationLoaderManager::class)
        ->and(app('translation.loader'))->toBeInstanceOf(NamespacedTranslationLoaderManager::class);
});
