<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Services;

use Illuminate\Support\Facades\Cache;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Traits\HasCacheKeyGeneration;

final class NewsletterService
{
    use HasCacheKeyGeneration;

    private const CACHE_TAG = 'newsletters';

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

    public function getCount(): int
    {
        $cacheKey = $this->generateCacheKey('newsletters', 'count', ['global']);

        return (int) $this->getCachedOrFresh(
            $cacheKey,
            fn(): int => Newsletter::count()
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
