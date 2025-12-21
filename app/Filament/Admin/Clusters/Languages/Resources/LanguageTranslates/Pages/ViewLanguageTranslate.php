<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\LanguageTranslateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewLanguageTranslate extends ViewRecord
{
    protected static string $resource = LanguageTranslateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.language_translate');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
