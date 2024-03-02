<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewLanguage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
