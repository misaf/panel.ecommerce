<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileBalanceResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListUserProfileBalance extends ListRecords
{
    protected static string $resource = UserProfileBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
