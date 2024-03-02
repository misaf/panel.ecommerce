<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductResource\Pages;

use App\Filament\Admin\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

final class EditProduct extends EditRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
