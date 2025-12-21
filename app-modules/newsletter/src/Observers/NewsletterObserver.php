<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;
use Misaf\Newsletter\Traits\HasCacheClearing;

/**
 * @method void saved(Newsletter $newsletter)
 * @method void deleted(Newsletter $newsletter)
 */
final class NewsletterObserver implements ShouldHandleEventsAfterCommit
{
    use HasCacheClearing;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterPostService $postService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    /**
     * @param Newsletter $newsletter
     * @return void
     */
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

    /**
     * @param Newsletter $newsletter
     * @return void
     */
    public function deleted(Newsletter $newsletter): void
    {
        $this->clearAllNewsletterCache(
            $this->newsletterService,
            $this->postService,
            $this->subscriberService
        );
    }
};
