<?php

declare(strict_types=1);

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

    expect(livewire(ListFaqs::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the faq categories table by every sortable column following the stored values', function (): void {
    $first = FaqCategoryFactory::new()->createOne();
    $second = FaqCategoryFactory::new()->createOne();

    expect(livewire(ListFaqCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
