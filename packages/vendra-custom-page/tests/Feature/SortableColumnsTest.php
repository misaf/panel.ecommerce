<?php

declare(strict_types=1);

use Awcodes\BadgeableColumn\Components\BadgeableColumn;
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

    $component = livewire(ListCustomPages::class)->call('loadTable');

    expect($component)
        ->toSortByEverySortableColumn([$first, $second])
        ->and($component->instance()->getTable()->getDefaultGroup())->toBeNull();
});

it('sorts the custom page categories table by every sortable column following the stored values', function (): void {
    $first = CustomPageCategoryFactory::new()->createOne();
    $second = CustomPageCategoryFactory::new()->createOne();

    expect(livewire(ListCustomPageCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('preloads custom page counts for category badges', function (): void {
    $category = CustomPageCategoryFactory::new()->createOne();
    CustomPageFactory::new()->count(2)->forCategory($category)->create();

    $records = livewire(ListCustomPageCategories::class)
        ->call('loadTable')
        ->instance()
        ->getTableRecords()
        ->getCollection();

    expect($records->firstWhere('id', $category->id)?->getAttribute('custom_pages_count'))->toBe(2);

    livewire(ListCustomPageCategories::class)
        ->call('loadTable')
        ->assertTableColumnExists(
            'name',
            function (BadgeableColumn $column): bool {
                $formattedState = (string) $column->formatState('Category');

                return str_contains($formattedState, 'badgeable-column-badge')
                    && str_contains($formattedState, '2');
            },
            $category,
        );
});
