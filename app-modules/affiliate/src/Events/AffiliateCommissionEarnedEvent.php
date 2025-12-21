<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

final class AffiliateCommissionEarnedEvent implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public int $userId, public int $commissionEarned) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('Affiliate.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'CommissionEarned';
    }
}
