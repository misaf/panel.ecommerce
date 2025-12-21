<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\LanguageTranslateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListLanguageTranslates extends ListRecords
{
    use Translatable;

    protected static string $resource = LanguageTranslateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.language_translate');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
