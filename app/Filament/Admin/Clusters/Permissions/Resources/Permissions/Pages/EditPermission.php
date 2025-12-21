<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Permissions\Resources\Permissions\Pages;

use App\Filament\Admin\Clusters\Permissions\Resources\Permissions\PermissionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.permission');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
