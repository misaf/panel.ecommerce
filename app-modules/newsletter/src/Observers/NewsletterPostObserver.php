<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;
use Misaf\Newsletter\Traits\HasCacheClearing;

/**
 * @method void saved(NewsletterPost $newsletterPost)
 * @method void deleted(NewsletterPost $newsletterPost)
 */
final class NewsletterPostObserver implements ShouldHandleEventsAfterCommit
{
    use HasCacheClearing;

    public function __construct(
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterPostService $postService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    /**
     * @param NewsletterPost $newsletterPost
     * @return void
     */
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

    /**
     * @param NewsletterPost $newsletterPost
     * @return void
     */
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
};
