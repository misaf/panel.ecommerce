<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\FaqResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use Misaf\Faq\Models\Faq;

final class ListFaqs extends ListRecords
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    /**
     * @param Builder<Faq> $query
     * @return Paginator<int, Faq>
     */

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
