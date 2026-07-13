<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\VendraNewsletter\Models\NewsletterPost;
use Misaf\VendraNewsletter\Services\NewsletterPostService;
use Misaf\VendraNewsletter\Services\NewsletterService;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;
use Misaf\VendraNewsletter\Traits\HasCacheClearing;

final class NewsletterPostObserver implements ShouldHandleEventsAfterCommit
{
    use HasCacheClearing;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterPostService $postService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    public function saved(NewsletterPost $newsletterPost): void
    {
        $this->clearNewsletterCache(
            'newsletter-post',
            true,
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }

    public function deleted(NewsletterPost $newsletterPost): void
    {
        $this->clearNewsletterCache(
            'newsletter-post',
            true,
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }
}
