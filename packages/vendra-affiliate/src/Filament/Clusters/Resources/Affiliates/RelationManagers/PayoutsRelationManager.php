<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\Tables\AffiliatePayoutTable;

final class PayoutsRelationManager extends RelationManager
{
    protected static string $relationship = 'payouts';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-affiliate::navigation.affiliate_payout');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return AffiliatePayoutTable::configure($table);
    }
}
