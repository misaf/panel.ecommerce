<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Data;

use Misaf\VendraSupport\Enums\SubscriptionChargeStatus;

final readonly class SubscriptionChargeResult
{
    public function __construct(
        public SubscriptionChargeStatus $status,
        public ?string $providerReference = null,
        public ?string $errorCode = null,
        public ?string $errorMessage = null,
    ) {}
}
