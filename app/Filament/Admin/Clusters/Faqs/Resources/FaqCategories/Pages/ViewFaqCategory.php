<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\FaqCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewFaqCategory extends ViewRecord
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
