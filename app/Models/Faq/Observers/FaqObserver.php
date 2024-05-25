<?php

declare(strict_types=1);

namespace App\Models\Faq\Observers;

use App\Models\Faq\Faq;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

final class FaqObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the Faq "created" event.
     *
     * @param Faq $faq
     */
    public function created(Faq $faq): void {}

    /**
     * Handle the Faq "deleted" event.
     *
     * @param Faq $faq
     */
    public function deleted(Faq $faq): void
    {
        Cache::forget('faq_row_count');
    }

    /**
     * Handle the Faq "force deleted" event.
     *
     * @param Faq $faq
     */
    public function forceDeleted(Faq $faq): void {}

    /**
     * Handle the Faq "restored" event.
     *
     * @param Faq $faq
     */
    public function restored(Faq $faq): void {}

    /**
     * Handle the Faq "saved" event.
     *
     * @param Faq $faq
     */
    public function saved(Faq $product): void
    {
        Cache::forget('faq_row_count');
    }

    /**
     * Handle the Faq "updated" event.
     *
     * @param Faq $faq
     */
    public function updated(Faq $faq): void {}
}
