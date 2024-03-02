<?php

declare(strict_types=1);

namespace App\Observers\Faq;

use App\Models\Faq\Faq;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

final class FaqObserver implements ShouldQueue
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

    public function created(Faq $faq): void {}

    public function deleted(Faq $faq): void
    {
        Cache::forget('faq_row_count');
    }

    public function forceDeleted(Faq $faq): void {}

    public function restored(Faq $faq): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function saved(Faq $product): void
    {
        Cache::forget('faq_row_count');
    }

    public function updated(Faq $faq): void {}
}
