<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Permissions\Pages;

use App\Filament\Admin\Clusters\Permissions\Resources\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('navigation.permission');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
