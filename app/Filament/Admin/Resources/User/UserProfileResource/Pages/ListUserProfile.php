<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListUserProfile extends ListRecords
{
    protected static string $resource = UserProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
