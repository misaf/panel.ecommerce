<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewFaq extends ViewRecord
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
