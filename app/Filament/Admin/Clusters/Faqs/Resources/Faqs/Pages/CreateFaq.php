<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\FaqResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateFaq extends CreateRecord
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
