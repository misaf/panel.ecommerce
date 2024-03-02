<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductCategoryResource\Pages;

use App\Filament\Admin\Resources\Product\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewProductCategory extends ViewRecord
{
    use Translatable;

    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
