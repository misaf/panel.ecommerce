<?php

declare(strict_types=1);

namespace Misaf\Language\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\Language\Facades\Language;
use Misaf\Language\Models\Language as LanguageModel;

final class LanguageObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * @param LanguageModel $language
     * @return void
     */
    public function saved(LanguageModel $language): void
    {
        Language::clearCache();
    }

    /**
     * @param LanguageModel $language
     * @return void
     */
    public function deleted(LanguageModel $language): void
    {
        Language::clearCache();
    }
}
