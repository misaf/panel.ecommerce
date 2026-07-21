<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Exceptions;

use Misaf\VendraSubscription\Models\Plan;
use RuntimeException;

final class PlanInUseException extends RuntimeException
{
    public static function forPlan(Plan $plan): self
    {
        return new self(sprintf(
            'Plan [%s] cannot be deleted because it is referenced by subscriptions.',
            $plan->id,
        ));
    }
}
