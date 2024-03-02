<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfilePhoneResource\Pages;

use App\Filament\Admin\Resources\User\UserProfilePhoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfilePhone extends ViewRecord
{
    protected static string $resource = UserProfilePhoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
