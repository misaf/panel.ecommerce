<?php

declare(strict_types=1);

namespace App\Observers\Currency;

use App\Models\Currency\Currency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class CurrencyObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(Currency $currency): void {}

    public function deleted(Currency $currency): void {}

    public function forceDeleted(Currency $currency): void {}

    public function restored(Currency $currency): void {}

    public function updated(Currency $currency): void {}
}
