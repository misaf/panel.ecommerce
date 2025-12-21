<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\Languages\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\Languages\LanguageResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.language');
    }
}
