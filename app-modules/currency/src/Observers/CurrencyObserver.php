<?php

declare(strict_types=1);

namespace Misaf\Currency\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class CurrencyObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
