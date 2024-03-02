<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListCurrency extends ListRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
