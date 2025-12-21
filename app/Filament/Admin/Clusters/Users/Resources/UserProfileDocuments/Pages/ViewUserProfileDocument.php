<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileDocuments\UserProfileDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfileDocument extends ViewRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.user_profile_document');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
