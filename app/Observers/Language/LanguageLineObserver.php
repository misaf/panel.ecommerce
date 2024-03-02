<?php

declare(strict_types=1);

namespace App\Observers\Language;

use App\Models\Language\LanguageLine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LanguageLineObserver implements ShouldQueue
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

    public function created(LanguageLine $languageLine): void {}

    public function deleted(LanguageLine $languageLine): void {}

    public function forceDeleted(LanguageLine $languageLine): void {}

    public function restored(LanguageLine $languageLine): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(LanguageLine $languageLine): void {}
}
