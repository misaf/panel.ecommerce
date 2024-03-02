<?php

declare(strict_types=1);

namespace App\Observers\Order;

use App\Models\Order\OrderProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class OrderProductObserver implements ShouldQueue
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

    public function created(OrderProduct $orderProduct): void {}

    public function deleted(OrderProduct $orderProduct): void {}

    public function forceDeleted(OrderProduct $orderProduct): void {}

    public function restored(OrderProduct $orderProduct): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(OrderProduct $orderProduct): void {}
}
