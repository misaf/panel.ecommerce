<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\FaqCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateFaqCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
