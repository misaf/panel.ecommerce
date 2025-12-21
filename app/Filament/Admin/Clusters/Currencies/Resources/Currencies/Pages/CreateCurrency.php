<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages;

use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\CurrencyResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.currency');
    }
}
