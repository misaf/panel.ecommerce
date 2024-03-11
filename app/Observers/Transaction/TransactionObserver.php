<?php

declare(strict_types=1);

namespace App\Observers\Transaction;

use App\Models\Transaction\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class TransactionObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(Transaction $transaction): void {}

    public function deleted(Transaction $transaction): void {}

    public function forceDeleted(Transaction $transaction): void {}

    public function restored(Transaction $transaction): void {}

    public function updated(Transaction $transaction): void {}
}
