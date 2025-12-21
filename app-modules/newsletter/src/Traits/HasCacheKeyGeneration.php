<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Traits;

trait HasCacheKeyGeneration
{
    /**
     * Generate a unique cache key for the given service and action, optionally including additional parameters.
     *
     * The generated key follows the format: {service}:{action}:{param1}_{param2}_...,
     * where parameters are concatenated with underscores for uniqueness.
     *
     * @param string $service   The name of the service or resource (e.g., 'newsletter', 'subscriber').
     * @param string $action    The specific action or context for the cache (e.g., 'count', 'all').
     * @param array<string, mixed> $parameters Optional list of parameters to further distinguish the cache key (e.g., IDs, status).
     * @return string           The generated cache key string.
     */
    private function generateCacheKey(string $service, string $action, array $parameters = []): string
    {
        $key = "{$service}:{$action}";

        if ( ! empty($parameters)) {
            $key .= ':' . implode('_', $parameters);
        }

        return $key;
    }
}
