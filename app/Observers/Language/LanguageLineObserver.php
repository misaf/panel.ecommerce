<?php

declare(strict_types=1);

namespace App\Observers\Language;

use App\Models\Language\LanguageLine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LanguageLineObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(LanguageLine $languageLine): void {}

    public function deleted(LanguageLine $languageLine): void {}

    public function forceDeleted(LanguageLine $languageLine): void {}

    public function restored(LanguageLine $languageLine): void {}

    public function updated(LanguageLine $languageLine): void {}
}
