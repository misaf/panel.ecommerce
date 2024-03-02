<?php

declare(strict_types=1);

namespace App\Observers\Transaction;

use App\Models\Transaction\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class TransactionObserver implements ShouldQueue
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

    public function created(Transaction $transaction): void {}

    public function deleted(Transaction $transaction): void {}

    public function forceDeleted(Transaction $transaction): void {}

    public function restored(Transaction $transaction): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(Transaction $transaction): void {}
}
