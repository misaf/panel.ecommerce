<?php

declare(strict_types=1);

namespace App\Observers\Faq;

use App\Models\Faq\FaqCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class FaqCategoryObserver implements ShouldQueue
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

    public function created(FaqCategory $faqCategory): void {}

    public function deleted(FaqCategory $faqCategory): void
    {
        $faqCategory->faqs()->delete();
    }

    public function forceDeleted(FaqCategory $faqCategory): void {}

    public function restored(FaqCategory $faqCategory): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(FaqCategory $faqCategory): void {}
}
