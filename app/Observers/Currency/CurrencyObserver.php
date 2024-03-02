<?php

declare(strict_types=1);

namespace App\Observers\Currency;

use App\Models\Currency\Currency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class CurrencyObserver implements ShouldQueue
{
    use InteractsWithQueue;

    //public $queue = 'listeners';

    public bool $afterCommit = true;

    public $maxExceptions = 5;

    public $tries = 5;

    public function backoff(): array
    {
        return [1, 5, 10, 30];
    }

    public function created(Currency $currency): void {}

    public function deleted(Currency $currency): void {}

    public function forceDeleted(Currency $currency): void {}

    public function restored(Currency $currency): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(Currency $currency): void {}
}
