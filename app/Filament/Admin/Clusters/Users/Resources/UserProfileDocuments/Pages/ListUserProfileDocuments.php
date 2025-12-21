<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\UserProfileDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListUserProfileDocuments extends ListRecords
{
    protected static string $resource = UserProfileDocumentResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.user_profile_document');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
