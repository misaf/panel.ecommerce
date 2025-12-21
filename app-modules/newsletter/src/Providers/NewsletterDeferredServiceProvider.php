<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterSendHistoryPostService;
use Misaf\Newsletter\Services\NewsletterSendHistoryService;
use Misaf\Newsletter\Services\NewsletterSendHistorySubscriberService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;
use Misaf\Newsletter\Services\NewsletterValidationService;

final class NewsletterDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton('newsletter-service', fn() => new NewsletterService());
        $this->app->singleton('newsletter-post-service', fn() => new NewsletterPostService());
        $this->app->singleton('newsletter-subscriber-service', fn() => new NewsletterSubscriberService());
        $this->app->singleton('newsletter-send-history-service', fn() => new NewsletterSendHistoryService());
        $this->app->singleton('newsletter-send-history-post-service', fn() => new NewsletterSendHistoryPostService());
        $this->app->singleton('newsletter-send-history-subscriber-service', fn() => new NewsletterSendHistorySubscriberService());
        $this->app->singleton('newsletter-validation-service', fn() => new NewsletterValidationService());
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            'newsletter-service',
            'newsletter-post-service',
            'newsletter-subscriber-service',
            'newsletter-send-history-service',
            'newsletter-send-history-post-service',
            'newsletter-send-history-subscriber-service',
            'newsletter-validation-service',
        ];
    }
}
