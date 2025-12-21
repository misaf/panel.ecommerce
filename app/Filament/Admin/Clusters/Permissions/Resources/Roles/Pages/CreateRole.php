<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Roles\Pages;

use App\Filament\Admin\Clusters\Permissions\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.role');
    }
}
