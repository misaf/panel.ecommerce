<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Currencies\Resources\Currencies\Pages;

use App\Filament\Admin\Clusters\Currencies\Resources\Currencies\CurrencyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('currency::navigation.currency');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            DeleteAction::make(),
        ];
    }
}
