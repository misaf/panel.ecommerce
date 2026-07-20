<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraNewsletter\Database\Factories\NewsletterFactory;
use Misaf\VendraNewsletter\Database\Factories\NewsletterSubscriberFactory;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\CreateNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\EditNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\ViewNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\CreateNewsletterSubscriber;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscriber;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\ViewNewsletterSubscriber;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the create newsletter page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateNewsletter::class)
        ->assertOk();
});

it('renders the edit newsletter page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $newsletter = NewsletterFactory::new()->createOne();

    livewire(EditNewsletter::class, ['record' => $newsletter->getKey()])
        ->assertOk();
});

it('renders the view newsletter page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $newsletter = NewsletterFactory::new()->createOne();

    livewire(ViewNewsletter::class, ['record' => $newsletter->getKey()])
        ->assertOk();
});

it('renders the create newsletter subscriber page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateNewsletterSubscriber::class)
        ->assertOk();
});

it('renders the edit newsletter subscriber page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $subscriber = NewsletterSubscriberFactory::new()->createOne();

    livewire(EditNewsletterSubscriber::class, ['record' => $subscriber->getKey()])
        ->assertOk();
});

it('renders the view newsletter subscriber page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $subscriber = NewsletterSubscriberFactory::new()->createOne();

    livewire(ViewNewsletterSubscriber::class, ['record' => $subscriber->getKey()])
        ->assertOk();
});
