<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\CreateLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\EditLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\ListLanguageLines;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\ListLanguages;
use Misaf\VendraLanguage\Models\Language;
use Misaf\VendraLanguage\Models\LanguageLine;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('shows package before group in the language line table', function (): void {
    $component = livewire(ListLanguageLines::class);

    expect(array_slice(array_keys($component->instance()->getTable()->getColumns()), 0, 3))
        ->toBe(['row', 'namespace', 'group']);
});

it('creates a discovered translation override for enabled locales', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);
    Language::query()->create(['locale' => 'de', 'position' => 2]);

    livewire(CreateLanguageLine::class)
        ->fillForm([
            'namespace' => 'vendra-language',
            'group'     => 'navigation',
            'key'       => 'language',
            'text'      => [
                'en' => 'Tenant Languages',
                'de' => '',
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(LanguageLine::query()->where([
        'namespace' => 'vendra-language',
        'group'     => 'navigation',
        'key'       => 'language',
    ])->value('text'))->toBe([
        'en' => 'Tenant Languages',
        'de' => '',
    ]);
});

it('accepts the browser state for a non-blank translation override', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);
    Language::query()->create(['locale' => 'de', 'position' => 2]);

    livewire(CreateLanguageLine::class)
        ->fillForm([
            'namespace' => 'vendra-language',
            'group'     => 'navigation',
            'key'       => 'language',
        ])
        ->set('data.text', [
            ['key' => 'en', 'value' => 'Tenant Languages'],
            ['key' => 'de', 'value' => ''],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(LanguageLine::query()->where([
        'namespace' => 'vendra-language',
        'group'     => 'navigation',
        'key'       => 'language',
    ])->value('text'))->toBe([
        'en' => 'Tenant Languages',
        'de' => '',
    ]);
});

it('requires at least one non-blank translation override', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);

    livewire(CreateLanguageLine::class)
        ->fillForm([
            'namespace' => 'vendra-language',
            'group'     => 'navigation',
            'key'       => 'language',
            'text'      => ['en' => '  '],
        ])
        ->call('create')
        ->assertHasFormErrors(['text']);
});

it('rejects translation groups that are not discovered from translation files', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);

    livewire(CreateLanguageLine::class)
        ->fillForm([
            'namespace' => 'vendra-language',
            'group'     => 'not-a-translation-file',
            'key'       => 'language',
            'text'      => ['en' => 'Tenant Languages'],
        ])
        ->call('create')
        ->assertHasFormErrors(['group']);
});

it('hydrates enabled locales without dropping stored disabled locales', function (): void {
    Language::query()->create(['locale' => 'en', 'position' => 1]);
    Language::query()->create(['locale' => 'de', 'position' => 2]);

    $languageLine = LanguageLine::query()->create([
        'namespace' => 'vendra-language',
        'group'     => 'navigation',
        'key'       => 'language',
        'text'      => [
            'en' => 'Tenant Languages',
            'fa' => 'زبان‌های مستأجر',
        ],
    ]);

    livewire(EditLanguageLine::class, ['record' => $languageLine->getKey()])
        ->assertSet('data.text', [
            ['key' => 'en', 'value' => 'Tenant Languages'],
            ['key' => 'de', 'value' => ''],
            ['key' => 'fa', 'value' => 'زبان‌های مستأجر'],
        ]);
});

it('sets a language as default from the table action', function (): void {
    $english = Language::query()->create(['locale' => 'en', 'position' => 1]);
    $german = Language::query()->create(['locale' => 'de', 'position' => 2]);

    livewire(ListLanguages::class)
        ->callAction(TestAction::make('setDefault')->table($german))
        ->assertNotified();

    expect($english->refresh()->is_default)->toBeFalse()
        ->and($german->refresh()->is_default)->toBeTrue();
});

it('shows locale coverage and language line progress in the tables', function (): void {
    $english = Language::query()->create(['locale' => 'en', 'position' => 1]);
    $german = Language::query()->create(['locale' => 'de', 'position' => 2]);

    $completeLanguageLine = LanguageLine::query()->create([
        'group' => 'navigation',
        'key'   => 'dashboard',
        'text'  => [
            'en' => 'Dashboard',
            'de' => 'Instrumententafel',
        ],
    ]);

    $partialLanguageLine = LanguageLine::query()->create([
        'group' => 'navigation',
        'key'   => 'settings',
        'text'  => [
            'en' => '',
            'de' => 'Einstellungen',
        ],
    ]);

    livewire(ListLanguages::class)
        ->loadTable()
        ->assertTableColumnStateSet('translation_coverage', '1 / 2', $english)
        ->assertTableColumnStateSet('translation_coverage', '2 / 2', $german);

    livewire(ListLanguageLines::class)
        ->loadTable()
        ->assertTableColumnStateSet('translation_progress', '2 / 2', $completeLanguageLine)
        ->assertTableColumnStateSet('translation_progress', '1 / 2', $partialLanguageLine);
});
