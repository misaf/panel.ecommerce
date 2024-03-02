<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfile extends ViewRecord
{
    protected static string $resource = UserProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
