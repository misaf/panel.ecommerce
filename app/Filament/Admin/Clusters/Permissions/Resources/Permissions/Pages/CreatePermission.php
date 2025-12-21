<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Permissions\Pages;

use App\Filament\Admin\Clusters\Permissions\Resources\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.permission');
    }
}
