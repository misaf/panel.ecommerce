<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyCategoryResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditCurrencyCategory extends EditRecord
{
    protected static string $resource = CurrencyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
