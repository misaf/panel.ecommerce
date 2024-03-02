<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageLineResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageLineResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewLanguageLine extends ViewRecord
{
    use Translatable;

    protected static string $resource = LanguageLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
