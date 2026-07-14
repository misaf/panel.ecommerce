<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Widgets;

use Misaf\VendraAffiliate\Models\Affiliate;

final class UserAffiliateOverviewWidget extends AffiliateStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected bool $isAffiliateScoped = true;

    public static function canView(): bool
    {
        return null !== self::authenticatedAffiliate();
    }

    protected function getAffiliateId(): ?int
    {
        return self::authenticatedAffiliate()?->id;
    }

    private static function authenticatedAffiliate(): ?Affiliate
    {
        $user = filament()->auth()->user();

        if (null === $user) {
            return null;
        }

        return Affiliate::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->first(['id']);
    }
}
