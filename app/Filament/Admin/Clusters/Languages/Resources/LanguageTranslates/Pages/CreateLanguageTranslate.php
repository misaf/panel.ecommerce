<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\Pages;

use App\Filament\Admin\Clusters\Languages\Resources\LanguageTranslates\LanguageTranslateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLanguageTranslate extends CreateRecord
{
    protected static string $resource = LanguageTranslateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.language_translate');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['text'] = [
            'en' => 'test-a',
            'fa' => 'text-b',
        ];

        return $data;
    }
}
