<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageLineResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageLineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListLanguageLine extends ListRecords
{
    use Translatable;

    protected static string $resource = LanguageLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
