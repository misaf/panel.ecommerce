<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductResource\Pages;

use App\Filament\Admin\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListProduct extends ListRecords
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
