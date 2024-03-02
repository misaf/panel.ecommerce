<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductCategoryResource\Pages;

use App\Filament\Admin\Resources\Product\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateProductCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
