<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewCurrency extends ViewRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
