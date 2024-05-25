<?php

declare(strict_types=1);

namespace App\Models\Language\Observers;

use App\Models\Language\LanguageLine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LanguageLineObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the LanguageLine "created" event.
     *
     * @param LanguageLine $languageLine
     */
    public function created(LanguageLine $languageLine): void {}

    /**
     * Handle the LanguageLine "deleted" event.
     *
     * @param LanguageLine $languageLine
     */
    public function deleted(LanguageLine $languageLine): void {}

    /**
     * Handle the LanguageLine "force deleted" event.
     *
     * @param LanguageLine $languageLine
     */
    public function forceDeleted(LanguageLine $languageLine): void {}

    /**
     * Handle the LanguageLine "restored" event.
     *
     * @param LanguageLine $languageLine
     */
    public function restored(LanguageLine $languageLine): void {}

    /**
     * Handle the LanguageLine "updated" event.
     *
     * @param LanguageLine $languageLine
     */
    public function updated(LanguageLine $languageLine): void {}
}
