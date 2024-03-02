<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageLineResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageLineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateLanguageLine extends CreateRecord
{
    use Translatable;

    protected static string $resource = LanguageLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
