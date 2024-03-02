<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductResource\Pages;

use App\Filament\Admin\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewProduct extends ViewRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
