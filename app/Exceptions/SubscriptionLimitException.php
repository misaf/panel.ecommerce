<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Reseller;
use RuntimeException;

final class SubscriptionLimitException extends RuntimeException
{
    public static function resellerDisabled(Reseller $reseller): self
    {
        return new self(sprintf(
            'Reseller [%s] is disabled.',
            $reseller->id,
        ));
    }

    public static function noActiveSubscription(Reseller $reseller): self
    {
        return new self(sprintf(
            'Reseller [%s] has no active subscription.',
            $reseller->id,
        ));
    }

    public static function propertyQuotaReached(Reseller $reseller, int $maxUnits): self
    {
        return new self(sprintf(
            'Reseller [%s] has reached its property limit of [%d].',
            $reseller->id,
            $maxUnits,
        ));
    }

    public static function planBelowUsage(Reseller $reseller, int $maxUnits, int $currentProperties): self
    {
        return new self(sprintf(
            'Reseller [%s] has %d property(s), which exceeds the [%d] allowed by the selected plan.',
            $reseller->id,
            $currentProperties,
            $maxUnits,
        ));
    }
}
