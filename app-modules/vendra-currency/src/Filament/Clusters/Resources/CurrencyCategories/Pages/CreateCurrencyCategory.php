<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource;

final class CreateCurrencyCategory extends CreateRecord
{
    protected static string $resource = CurrencyCategoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('vendra-currency::navigation.currency_category');
    }
}
