<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterPost;
use Misaf\VendraNewsletter\Traits\HasCacheKeyGeneration;

final class NewsletterPostService
{
    use HasCacheKeyGeneration;

    private const CACHE_TAG = 'newsletter-post';

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
     * Get base query for newsletter posts
     *
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

    public function getCount(?Newsletter $newsletter = null): int
    {
        $cacheKey = $this->generateCacheKey('newsletter_post', 'count', $newsletter ? [$newsletter->id] : ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => $this->getBaseQuery($newsletter)->count()
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
