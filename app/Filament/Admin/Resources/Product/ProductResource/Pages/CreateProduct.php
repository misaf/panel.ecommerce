<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductResource\Pages;

use App\Filament\Admin\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateProduct extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
