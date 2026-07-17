<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\CreateCustomPageCategory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\ListCustomPageCategories;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\CreateCustomPage;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\EditCustomPage;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\ListCustomPages;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\ViewCustomPage;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('renders the create custom page page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateCustomPage::class)
        ->assertOk();
});

it('renders the edit custom page page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $customPage = CustomPageFactory::new()->createOne();

    livewire(EditCustomPage::class, ['record' => $customPage->getKey()])
        ->assertOk();
});

it('renders the view custom page page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $customPage = CustomPageFactory::new()->createOne();

    livewire(ViewCustomPage::class, ['record' => $customPage->getKey()])
        ->assertOk();
});

it('renders the create custom page category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateCustomPageCategory::class)
        ->assertOk();
});

it('renders the reorderable custom pages table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $customPage = CustomPageFactory::new()->createOne();

    livewire(ListCustomPages::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$customPage]);
});

it('renders the reorderable custom page categories table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $customPageCategory = CustomPageCategoryFactory::new()->createOne();

    livewire(ListCustomPageCategories::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$customPageCategory]);
});
