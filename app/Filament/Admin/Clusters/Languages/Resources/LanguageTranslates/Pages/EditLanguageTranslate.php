<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\LanguageTranslateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditLanguageTranslate extends EditRecord
{
    protected static string $resource = LanguageTranslateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.language_translate');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['text'] = [
            'en' => 'b5523',
            'fa' => 'b5523',
        ];

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
