<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages;

use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\CurrencyCategoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCurrencyCategory extends CreateRecord
{
    protected static string $resource = CurrencyCategoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.currency_category');
    }
}
