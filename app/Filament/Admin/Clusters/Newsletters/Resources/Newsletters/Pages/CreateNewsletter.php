<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages;

use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\NewsletterResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateNewsletter extends CreateRecord
{
    use Translatable;

    protected static string $resource = NewsletterResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('newsletter::navigation.newsletter');
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
