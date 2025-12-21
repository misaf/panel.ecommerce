<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages;

use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\FaqResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewFaq extends ViewRecord
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
