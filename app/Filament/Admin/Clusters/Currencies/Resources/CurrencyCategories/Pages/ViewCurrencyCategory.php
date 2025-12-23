<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\Pages;

use App\Filament\Admin\Clusters\Currencies\Resources\CurrencyCategories\CurrencyCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewCurrencyCategory extends ViewRecord
{
    protected static string $resource = CurrencyCategoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('currency::navigation.currency_category');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
