<?php

declare(strict_types=1);

namespace App\Observers\Order;

use App\Models\Order\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class OrderObserver implements ShouldQueue
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

    public function created(Order $order): void {}

    public function deleted(Order $order): void {}

    public function forceDeleted(Order $order): void {}

    public function restored(Order $order): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(Order $order): void {}
}
