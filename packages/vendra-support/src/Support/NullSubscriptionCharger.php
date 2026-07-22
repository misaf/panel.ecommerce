<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Misaf\VendraSupport\Contracts\SubscriptionCharger;
use Misaf\VendraSupport\Data\SubscriptionCharge;
use Misaf\VendraSupport\Data\SubscriptionChargeResult;
use Misaf\VendraSupport\Enums\SubscriptionChargeStatus;

final class NullSubscriptionCharger implements SubscriptionCharger
{
    public function provider(): string
    {
        return 'none';
    }

    public function available(): bool
    {
        return false;
    }

    public function charge(SubscriptionCharge $charge): SubscriptionChargeResult
    {
        return $this->unavailableResult();
    }

    public function retrieve(SubscriptionCharge $charge): SubscriptionChargeResult
    {
        return $this->unavailableResult();
    }

    private function unavailableResult(): SubscriptionChargeResult
    {
        return new SubscriptionChargeResult(
            SubscriptionChargeStatus::Failed,
            errorCode: 'provider_unavailable',
            errorMessage: 'No subscription payment provider is available.',
        );
    }
}
