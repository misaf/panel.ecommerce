<?php

declare(strict_types=1);

namespace App\Observers\Language;

use App\Models\Language\Language;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class LanguageObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(Language $language): void {}

    public function deleted(Language $language): void {}

    public function forceDeleted(Language $language): void {}

    public function restored(Language $language): void {}

    public function updated(Language $language): void {}
}
