<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraLanguage\Database\Factories\LanguageFactory;
use Misaf\VendraLanguage\Database\Factories\LanguageLineFactory;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\CreateLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\EditLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\ViewLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\CreateLanguage;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\EditLanguage;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\ListLanguages;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\ViewLanguage;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the create language page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateLanguage::class)
        ->assertOk();
});

it('renders the edit language page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $language = LanguageFactory::new()->createOne();

    livewire(EditLanguage::class, ['record' => $language->getKey()])
        ->assertOk();
});

it('renders the view language page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $language = LanguageFactory::new()->createOne();

    livewire(ViewLanguage::class, ['record' => $language->getKey()])
        ->assertOk();
});

it('renders the create language line page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateLanguageLine::class)
        ->assertOk();
});

it('renders the edit language line page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $languageLine = LanguageLineFactory::new()->createOne();

    livewire(EditLanguageLine::class, ['record' => $languageLine->getKey()])
        ->assertOk();
});

it('renders the view language line page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $languageLine = LanguageLineFactory::new()->createOne();

    livewire(ViewLanguageLine::class, ['record' => $languageLine->getKey()])
        ->assertOk();
});

it('renders the reorderable languages table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $language = LanguageFactory::new()->createOne();

    livewire(ListLanguages::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$language]);
});
