<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Data;

use Illuminate\Database\Eloquent\Model;

final readonly class SubscriptionCharge
{
    public function __construct(
        public Model $payer,
        public int $amount,
        public string $currencyCode,
        public string $reference,
        public ?string $providerReference = null,
    ) {}
}
