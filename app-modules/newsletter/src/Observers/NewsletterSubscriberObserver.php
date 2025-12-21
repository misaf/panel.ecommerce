<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;
use Misaf\Newsletter\Traits\HasCacheClearing;

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

    /**
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return void
     */
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

    /**
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return void
     */
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
};
