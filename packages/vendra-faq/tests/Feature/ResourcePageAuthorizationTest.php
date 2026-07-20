<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraFaq\Database\Factories\FaqCategoryFactory;
use Misaf\VendraFaq\Database\Factories\FaqFactory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\CreateFaqCategory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\ListFaqCategories;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\CreateFaq;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\EditFaq;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\ListFaqs;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\ViewFaq;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('renders the create faq page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateFaq::class)
        ->assertOk();
});

it('renders the edit faq page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $faq = FaqFactory::new()->createOne();

    livewire(EditFaq::class, ['record' => $faq->getKey()])
        ->assertOk();
});

it('renders the view faq page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $faq = FaqFactory::new()->createOne();

    livewire(ViewFaq::class, ['record' => $faq->getKey()])
        ->assertOk();
});

it('renders the create faq category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateFaqCategory::class)
        ->assertOk();
});

it('renders the reorderable faqs table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $faq = FaqFactory::new()->createOne();

    livewire(ListFaqs::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$faq]);
});

it('renders the reorderable faq categories table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $faqCategory = FaqCategoryFactory::new()->createOne();

    livewire(ListFaqCategories::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$faqCategory]);
});
