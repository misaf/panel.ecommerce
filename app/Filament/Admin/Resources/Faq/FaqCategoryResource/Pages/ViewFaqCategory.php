<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqCategoryResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewFaqCategory extends ViewRecord
{
    use Translatable;

    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
