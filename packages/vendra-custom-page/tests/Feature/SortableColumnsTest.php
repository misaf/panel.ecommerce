<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\ListCustomPageCategories;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\ListCustomPages;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('sorts the custom pages table by every sortable column following the stored values', function (): void {
    $customPageCategory = CustomPageCategoryFactory::new()->createOne();

    $first = CustomPageFactory::new()->forCategory($customPageCategory)->createOne();
    $second = CustomPageFactory::new()->forCategory($customPageCategory)->createOne();

    expect(livewire(ListCustomPages::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the custom page categories table by every sortable column following the stored values', function (): void {
    $first = CustomPageCategoryFactory::new()->createOne();
    $second = CustomPageCategoryFactory::new()->createOne();

    expect(livewire(ListCustomPageCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
