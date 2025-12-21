<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\FaqCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use Misaf\Faq\Models\FaqCategory;

final class ListFaqCategories extends ListRecords
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    /**
     * @param Builder<FaqCategory> $query
     * @return Paginator<int, FaqCategory>
     */

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
