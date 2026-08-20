<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Observers;

use Misaf\VendraStore\Models\StoreDomain;
use Misaf\VendraStore\Support\StorefrontOrigins;

/**
 * Keeps the API's CORS allowlist in step with the domains that back it.
 *
 * A domain going active or inactive changes who may call the API, so the cached
 * allowlist must not outlive the change. This is model-lifecycle behaviour and
 * belongs in an observer rather than as two closures in the service provider's
 * boot method.
 */
final class StoreDomainObserver
{
    public function saved(StoreDomain $domain): void
    {
        StorefrontOrigins::forget();
    }

    public function deleted(StoreDomain $domain): void
    {
        StorefrontOrigins::forget();
    }
}
