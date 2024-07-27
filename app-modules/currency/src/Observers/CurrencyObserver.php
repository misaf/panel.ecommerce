<?php

declare(strict_types=1);

namespace Termehsoft\Currency\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\Currency\Models\Currency;

final class CurrencyObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the Currency "deleted" event.
     *
     * @param Currency $currency
     * @return void
     */
    public function deleted(Currency $currency): void
    {
        $this->clearCaches($currency);
    }

    /**
     * Handle the Currency "saved" event.
     *
     * @param Currency $currency
     * @return void
     */
    public function saved(Currency $currency): void
    {
        $this->clearCaches($currency);
    }

    /**
     * Clear relevant caches.
     *
     * @param Currency $currency
     * @return void
     */
    private function clearCaches(Currency $currency): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the currency row count cache.
     *
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('currency-row-count');
    }
}
