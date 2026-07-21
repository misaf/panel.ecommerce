<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Contracts\SubscriptionCharger;

final class NullSubscriptionCharger implements SubscriptionCharger
{
    public function available(): bool
    {
        return false;
    }

    public function charge(Model $payer, int $amount, string $currencyCode, string $reference): bool
    {
        return false;
    }
}
