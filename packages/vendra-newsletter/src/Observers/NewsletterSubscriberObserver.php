<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraNewsletter\Services\NewsletterPostService;
use Misaf\VendraNewsletter\Services\NewsletterService;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;
use Misaf\VendraNewsletter\Traits\HasCacheClearing;

/**
 * @method void saved(NewsletterSubscriber $newsletterSubscriber)
 * @method void deleted(NewsletterSubscriber $newsletterSubscriber)
 */
final class NewsletterSubscriberObserver implements ShouldHandleEventsAfterCommit
{
    use HasCacheClearing;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterPostService $postService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    public function saved(NewsletterSubscriber $newsletterSubscriber): void
    {
        $this->clearNewsletterCache(
            'newsletter-subscriber',
            true,
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }

    public function deleted(NewsletterSubscriber $newsletterSubscriber): void
    {
        $newsletterSubscriber->newsletters()->detach();

        $this->clearNewsletterCache(
            'newsletter-subscriber',
            true,
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }
}
