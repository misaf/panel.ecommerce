<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
