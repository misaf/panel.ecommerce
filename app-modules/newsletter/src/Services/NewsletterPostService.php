<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Traits\HasCacheKeyGeneration;

final class NewsletterPostService
{
    use HasCacheKeyGeneration;

    private const CACHE_TAG = 'newsletter-post';

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
     * Get base query for newsletter posts
     *
     * @param Newsletter|null $newsletter
     * @return Builder<NewsletterPost>
     */
    private function getBaseQuery(?Newsletter $newsletter = null): Builder
    {
        $query = NewsletterPost::query();

        if (null !== $newsletter) {
            $query->where('newsletter_id', $newsletter->id);
        }

        return $query;
    }

    /**
     * Get count of posts (optionally for a specific newsletter)
     *
     * @param Newsletter|null $newsletter
     * @return int
     */
    public function getCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_post', 'count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)->count()
        );
    }

    /**
     * Clear all newsletter post cache
     *
     * Removes all cached data for posts.
     * This is automatically called when posts are saved or deleted.
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
