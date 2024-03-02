<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfilePhoneResource\Pages;

use App\Filament\Admin\Resources\User\UserProfilePhoneResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserProfilePhone extends CreateRecord
{
    protected static string $resource = UserProfilePhoneResource::class;

    protected function afterCreate(): void
    {
        $this->record->setStatus($this->data['status']);
    }
}
