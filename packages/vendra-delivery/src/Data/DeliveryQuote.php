<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Data;

use Misaf\VendraDelivery\Models\DeliveryZone;

/**
 * What one dropped pin costs to deliver to.
 *
 * `requiresQuote` marks an address the studio prices by hand — beyond the
 * usual range — so checkout must refuse it rather than invent a fee.
 */
final readonly class DeliveryQuote
{
    public function __construct(
        public ?DeliveryZone $zone,
        public float $distanceKm,
        public int $feeAmount,
        public string $currencyCode,
        public bool $requiresQuote,
    ) {}

    /**
     * The quote used when no band covers the point at all.
     */
    public static function outOfRange(float $distanceKm, string $currencyCode): self
    {
        return new self(
            zone: null,
            distanceKm: $distanceKm,
            feeAmount: 0,
            currencyCode: $currencyCode,
            requiresQuote: true,
        );
    }

    public function isDeliverable(): bool
    {
        return ! $this->requiresQuote;
    }
}
