<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUserProfile extends CreateRecord
{
    protected static string $resource = UserProfileResource::class;
}
