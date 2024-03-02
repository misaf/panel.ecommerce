<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission\PermissionResource\Pages;

use App\Filament\Admin\Resources\Permission\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
