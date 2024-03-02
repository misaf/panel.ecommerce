<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileBalanceResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditUserProfileBalance extends EditRecord
{
    protected static string $resource = UserProfileBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
