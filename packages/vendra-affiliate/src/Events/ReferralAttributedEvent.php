<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ReferralAttributedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public int $affiliateId,
        public int $userId,
    ) {}
}
