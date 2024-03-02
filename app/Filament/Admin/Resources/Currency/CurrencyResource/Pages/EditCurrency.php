<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

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
