<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Product\ProductCategoryResource\Pages;

use App\Filament\Admin\Resources\Product\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListProductCategory extends ListRecords
{
    use Translatable;

    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
