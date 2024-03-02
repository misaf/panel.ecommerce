<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqCategoryResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateFaqCategory extends CreateRecord
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
