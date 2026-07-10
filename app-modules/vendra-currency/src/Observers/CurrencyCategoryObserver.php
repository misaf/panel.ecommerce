<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraCurrency\Models\CurrencyCategory;

final class CurrencyCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(CurrencyCategory $currencyCategory): void
    {
        $currencyCategory->currencies()->delete();
    }
}
