<?php

declare(strict_types=1);

namespace Termehsoft\Currency\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\Currency\Models\CurrencyCategory;

final class CurrencyCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the CurrencyCategory "deleted" event.
     *
     * @param CurrencyCategory $currencyCategory
     * @return void
     */
    public function deleted(CurrencyCategory $currencyCategory): void
    {
        $currencyCategory->currencies()->delete();

        $this->clearCaches($currencyCategory);
    }

    /**
     * Handle the CurrencyCategory "saved" event.
     *
     * @param CurrencyCategory $currenncyCategory
     * @return void
     */
    public function saved(CurrencyCategory $currenncyCategory): void
    {
        $this->clearCaches($currenncyCategory);
    }

    /**
     * Clear relevant caches.
     *
     * @param CurrencyCategory $currenncyCategory
     * @return void
     */
    private function clearCaches(CurrencyCategory $currenncyCategory): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the currenncy category row count cache.
     *
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('currency-category-row-count');
    }
}
