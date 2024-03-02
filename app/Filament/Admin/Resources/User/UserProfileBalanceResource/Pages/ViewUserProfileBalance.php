<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileBalanceResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewUserProfileBalance extends ViewRecord
{
    protected static string $resource = UserProfileBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
