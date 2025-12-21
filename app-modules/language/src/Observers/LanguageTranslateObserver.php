<?php

declare(strict_types=1);

namespace Misaf\Language\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LanguageTranslateObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
