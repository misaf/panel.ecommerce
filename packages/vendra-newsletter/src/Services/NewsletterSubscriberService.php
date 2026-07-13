<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraNewsletter\Traits\HasCacheKeyGeneration;

final class NewsletterSubscriberService
{
    use HasCacheKeyGeneration;

    public const CACHE_TAG = 'newsletter-subscriber';

    private function shouldSkipCache(): bool
    {
        return ! Cache::supportsTags();
    }

    private function getCachedOrFresh(string $cacheKey, callable $callback): mixed
    {
        if ($this->shouldSkipCache()) {
            return $callback();
        }

        return Cache::tags(self::CACHE_TAG)->rememberForever(
            $cacheKey,
            $callback
        );
    }

    /**
     * Get base query for newsletter subscribers
     *
     * @return Builder<NewsletterSubscriber>
     */
    private function getBaseQuery(?Newsletter $newsletter = null): Builder
    {
        $query = NewsletterSubscriber::query();

        if (null !== $newsletter) {
            $query->whereRelation('newsletters', 'newsletter_id', $newsletter->id);
        }

        return $query;
    }

    public function getCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_subscriber', 'count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)->count()
        );
    }

    public function getSubscribedCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_subscriber', 'subscribed_count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)
                ->whereHas('newsletters', function ($query) use ($newsletter): void {
                    if ($newsletter) {
                        $query->where('newsletter_id', $newsletter->id);
                    }
                    $query->whereNull('unsubscribed_at');
                })
                ->count()
        );
    }

    public function getUnsubscribedCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_subscriber', 'unsubscribed_count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)
                ->whereHas('newsletters', function ($query) use ($newsletter): void {
                    if ($newsletter) {
                        $query->where('newsletter_id', $newsletter->id);
                    }
                    $query->whereNotNull('unsubscribed_at');
                })
                ->count()
        );
    }

    public function clearCache(): void
    {
        if ($this->shouldSkipCache()) {
            return;
        }

        Cache::tags(self::CACHE_TAG)->flush();
    }
}
