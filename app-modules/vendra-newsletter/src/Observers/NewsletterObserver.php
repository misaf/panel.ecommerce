<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Services\NewsletterPostService;
use Misaf\VendraNewsletter\Services\NewsletterService;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;
use Misaf\VendraNewsletter\Traits\HasCacheClearing;

final class NewsletterObserver implements ShouldHandleEventsAfterCommit
{
    use HasCacheClearing;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterPostService $postService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    public function saved(Newsletter $newsletter): void
    {
        $this->clearNewsletterCache(
            'newsletter',
            true,
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }

    public function deleted(Newsletter $newsletter): void
    {
        $this->clearAllNewsletterCache(
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }
}
