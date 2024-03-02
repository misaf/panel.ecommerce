<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileDocumentResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfileDocument extends ViewRecord
{
    protected static string $resource = UserProfileDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
