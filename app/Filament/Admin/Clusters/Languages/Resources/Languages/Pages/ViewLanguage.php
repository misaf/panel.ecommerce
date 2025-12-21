<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\Languages\LanguageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewLanguage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.language');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
