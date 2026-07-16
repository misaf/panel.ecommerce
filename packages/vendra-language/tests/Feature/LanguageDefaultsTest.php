<?php

declare(strict_types=1);

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraLanguage\Actions\SetDefaultLanguage;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraSupport\Support\TenantAwareness;

use function Pest\Laravel\mock;

beforeEach(function (): void {
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturn(1);

    app()->instance(TenantResolver::class, $tenantResolver);
});

it('makes the first enabled language the default', function (): void {
    $language = Language::query()->create([
        'locale'     => 'en',
        'is_default' => false,
        'position'   => 1,
    ]);

    expect($language->is_default)->toBeTrue();
});

it('does not allow the only default language to be unset', function (): void {
    $language = Language::query()->create([
        'locale'     => 'en',
        'is_default' => true,
        'position'   => 1,
    ]);

    $language->update(['is_default' => false]);

    expect($language->refresh()->is_default)->toBeTrue();
});

it('promotes the first ordered language when the default is deleted', function (): void {
    $default = Language::query()->create([
        'locale'     => 'en',
        'is_default' => true,
        'position'   => 2,
    ]);

    $next = Language::query()->create([
        'locale'     => 'de',
        'is_default' => false,
        'position'   => 1,
    ]);

    $default->delete();

    expect($next->refresh()->is_default)->toBeTrue();
});

it('switches the default language through the domain action', function (): void {
    $english = Language::query()->create([
        'locale'     => 'en',
        'is_default' => true,
        'position'   => 1,
    ]);

    $german = Language::query()->create([
        'locale'     => 'de',
        'is_default' => false,
        'position'   => 2,
    ]);

    (new SetDefaultLanguage())->execute($german);

    expect($english->refresh()->is_default)->toBeFalse()
        ->and($german->refresh()->is_default)->toBeTrue();
});

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

it('backfills the first ordered language for every tenant without a default', function (): void {
    $currentTenantId = 1;
    $tenantResolver = mock(TenantResolver::class);

    $tenantResolver->shouldReceive('available')->andReturnTrue();
    $tenantResolver->shouldReceive('current')->andReturnNull();
    $tenantResolver->shouldReceive('currentId')->andReturnUsing(function () use (&$currentTenantId): int {
        return $currentTenantId;
    });

    app()->instance(TenantResolver::class, $tenantResolver);

    $tenantOneEnglish = Language::query()->create(['locale' => 'en', 'is_default' => false, 'position' => 2]);
    $tenantOneGerman = Language::query()->create(['locale' => 'de', 'is_default' => false, 'position' => 1]);

    $currentTenantId = 2;

    $tenantTwoEnglish = Language::query()->create(['locale' => 'en', 'is_default' => false, 'position' => 1]);
    $tenantTwoPersian = Language::query()->create(['locale' => 'fa', 'is_default' => false, 'position' => 2]);

    DB::table('languages')->update(['is_default' => false]);
    DB::table('languages')->where('id', $tenantOneEnglish->getKey())->update(['position' => 2]);
    DB::table('languages')->where('id', $tenantOneGerman->getKey())->update(['position' => 1]);
    DB::table('languages')->where('id', $tenantTwoEnglish->getKey())->update(['position' => 1]);
    DB::table('languages')->where('id', $tenantTwoPersian->getKey())->update(['position' => 2]);

    $migration = require __DIR__ . '/../../database/migrations/backfill_language_defaults.php.stub';
    $migration->up();

    expect(DB::table('languages')->where('tenant_id', 1)->where('is_default', true)->value('locale'))->toBe('de')
        ->and(DB::table('languages')->where('tenant_id', 2)->where('is_default', true)->value('locale'))->toBe('en');
});
