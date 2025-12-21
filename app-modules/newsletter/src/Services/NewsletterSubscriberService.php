<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Newsletter\Traits\HasCacheKeyGeneration;

final class NewsletterSubscriberService
{
    use HasCacheKeyGeneration;

    public const CACHE_TAG = 'newsletter-subscriber';

    /**
     * Check if cache should be skipped
     *
     * @return bool
     */
    private function shouldSkipCache(): bool
    {
        return ! Cache::supportsTags();
    }

    /**
     * Get cached or fresh data
     *
     * @param string $cacheKey
     * @param callable $callback
     * @return mixed
     */
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
     * @param Newsletter|null $newsletter
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

    /**
     * Get total count of subscribers (optionally for a specific newsletter)
     *
     * @param Newsletter|null $newsletter
     * @return int
     */
    public function getCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_subscriber', 'count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)->count()
        );
    }

    /**
     * Get count of subscribers who are subscribed (optionally for a specific newsletter)
     */
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

    /**
     * Get count of subscribers who are unsubscribed (optionally for a specific newsletter)
     *
     * @param Newsletter|null $newsletter
     * @return int
     */
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

    /**
     * Clear all newsletter subscriber cache
     *
     * Removes all cached data for subscribers.
     * This is automatically called when subscribers are saved or deleted.
     *
     * @return void
     */
    public function clearCache(): void
    {
        if ($this->shouldSkipCache()) {
            return;
        }

        Cache::tags(self::CACHE_TAG)->flush();
    }
}
