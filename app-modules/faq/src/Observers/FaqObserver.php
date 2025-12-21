<?php

declare(strict_types=1);

namespace Misaf\Faq\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class FaqObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
