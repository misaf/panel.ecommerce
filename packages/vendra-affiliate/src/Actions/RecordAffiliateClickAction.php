<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Actions;

use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliate\Models\AffiliateClick;
use Spatie\QueueableAction\QueueableAction;

final class RecordAffiliateClickAction
{
    use QueueableAction;

    public function execute(
        Affiliate $affiliate,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $referer = null,
        ?string $landingUrl = null,
    ): AffiliateClick {
        return $affiliate->clicks()->create([
            'ip_address'  => $ipAddress,
            'user_agent'  => null === $userAgent ? null : mb_substr($userAgent, 0, 255),
            'referer'     => null === $referer ? null : mb_substr($referer, 0, 255),
            'landing_url' => null === $landingUrl ? null : mb_substr($landingUrl, 0, 255),
        ]);
    }
}
