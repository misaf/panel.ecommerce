<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqCategoryResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListFaqCategory extends ListRecords
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
