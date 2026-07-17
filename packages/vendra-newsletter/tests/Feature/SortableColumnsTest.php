<?php

declare(strict_types=1);

use Misaf\VendraNewsletter\Database\Factories\NewsletterFactory;
use Misaf\VendraNewsletter\Database\Factories\NewsletterSubscriberFactory;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\ListNewsletters;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('sorts the newsletters table by every sortable column following the stored values', function (): void {
    $first = NewsletterFactory::new()->createOne();
    $second = NewsletterFactory::new()->createOne();

    expect(livewire(ListNewsletters::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the newsletter subscribers table by every sortable column following the stored values', function (): void {
    $first = NewsletterSubscriberFactory::new()->createOne();
    $second = NewsletterSubscriberFactory::new()->createOne();

    expect(livewire(ListNewsletterSubscribers::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
