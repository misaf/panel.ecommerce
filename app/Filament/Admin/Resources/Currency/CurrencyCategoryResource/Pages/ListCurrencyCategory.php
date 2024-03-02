<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyCategoryResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListCurrencyCategory extends ListRecords
{
    protected static string $resource = CurrencyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
