<?php

declare(strict_types=1);

namespace App\Observers\Currency;

use App\Models\Currency\CurrencyCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class CurrencyCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(CurrencyCategory $currencyCategory): void {}

    public function deleted(CurrencyCategory $currencyCategory): void
    {
        $currencyCategory->currencies()->delete();
    }

    public function forceDeleted(CurrencyCategory $currencyCategory): void {}

    public function restored(CurrencyCategory $currencyCategory): void {}

    public function updated(CurrencyCategory $currencyCategory): void {}
}
