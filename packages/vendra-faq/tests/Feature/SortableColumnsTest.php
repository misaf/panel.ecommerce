<?php

declare(strict_types=1);

use Awcodes\BadgeableColumn\Components\BadgeableColumn;
use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraFaq\Database\Factories\FaqCategoryFactory;
use Misaf\VendraFaq\Database\Factories\FaqFactory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\ListFaqCategories;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\ListFaqs;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('sorts the faqs table by every sortable column following the stored values', function (): void {
    $faqCategory = FaqCategoryFactory::new()->createOne();

    $first = FaqFactory::new()->forCategory($faqCategory)->createOne();
    $second = FaqFactory::new()->forCategory($faqCategory)->createOne();

    $component = livewire(ListFaqs::class)->call('loadTable');

    expect($component)
        ->toSortByEverySortableColumn([$first, $second])
        ->and($component->instance()->getTable()->getDefaultGroup())->toBeNull();
});

it('sorts the faq categories table by every sortable column following the stored values', function (): void {
    $first = FaqCategoryFactory::new()->createOne();
    $second = FaqCategoryFactory::new()->createOne();

    expect(livewire(ListFaqCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('preloads faq counts for category badges', function (): void {
    $category = FaqCategoryFactory::new()->createOne();
    FaqFactory::new()->count(2)->forCategory($category)->create();

    $records = livewire(ListFaqCategories::class)
        ->call('loadTable')
        ->instance()
        ->getTableRecords()
        ->getCollection();

    expect($records->firstWhere('id', $category->id)?->getAttribute('faqs_count'))->toBe(2);

    livewire(ListFaqCategories::class)
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
