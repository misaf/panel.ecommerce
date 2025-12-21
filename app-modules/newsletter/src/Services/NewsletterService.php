<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Services;

use Illuminate\Support\Facades\Cache;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Traits\HasCacheKeyGeneration;

final class NewsletterService
{
    use HasCacheKeyGeneration;

    private const CACHE_TAG = 'newsletters';

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
     * Get total count of all newsletters
     *
     * @return int
     */
    public function getCount(): int
    {
        $cacheKey = $this->generateCacheKey('newsletters', 'count', ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => Newsletter::count()
        );
    }

    /**
     * Clear all newsletter cache
     *
     * Removes all cached data for newsletters.
     * This is automatically called when newsletters are saved or deleted.
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
