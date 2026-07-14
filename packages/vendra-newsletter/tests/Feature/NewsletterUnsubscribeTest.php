<?php

declare(strict_types=1);

use Misaf\VendraNewsletter\Database\Factories\NewsletterSubscriberFactory;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

it('unsubscribes a subscriber when their unsubscribe link is visited', function (): void {
    $subscriber = NewsletterSubscriberFactory::new()->subscribed()->create();

    $response = $this->get(route('vendra-newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]));

    $response->assertOk();

    expect($subscriber->refresh()->unsubscribed_at)->not->toBeNull()
        ->and($subscriber->isSubscribed())->toBeFalse();
});

it('keeps an already unsubscribed recipient unchanged', function (): void {
    $subscriber = NewsletterSubscriberFactory::new()->unsubscribed()->create();
    $unsubscribedAt = $subscriber->unsubscribed_at;

    $this->get(route('vendra-newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]))
        ->assertOk();

    expect($subscriber->refresh()->unsubscribed_at->equalTo($unsubscribedAt))->toBeTrue();
});

it('shows the unknown page and changes nothing for an invalid token', function (): void {
    NewsletterSubscriberFactory::new()->subscribed()->count(2)->create();

    $this->get(route('vendra-newsletter.unsubscribe', ['token' => 'invalid-token']))
        ->assertOk();

    expect(NewsletterSubscriber::query()->unsubscribed()->count())->toBe(0);
});
