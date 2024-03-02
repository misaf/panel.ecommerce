<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\RoleResource\Pages;

use App\Filament\Admin\Resources\Permission\RoleResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}
