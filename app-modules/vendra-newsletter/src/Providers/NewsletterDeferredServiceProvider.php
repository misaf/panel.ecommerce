<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Misaf\VendraNewsletter\Services\NewsletterPostService;
use Misaf\VendraNewsletter\Services\NewsletterSendHistoryPostService;
use Misaf\VendraNewsletter\Services\NewsletterSendHistoryService;
use Misaf\VendraNewsletter\Services\NewsletterSendHistorySubscriberService;
use Misaf\VendraNewsletter\Services\NewsletterService;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;
use Misaf\VendraNewsletter\Services\NewsletterValidationService;

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
