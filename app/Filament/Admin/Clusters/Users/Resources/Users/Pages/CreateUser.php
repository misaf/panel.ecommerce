<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\Users\Pages;

use App\Filament\Admin\Clusters\Users\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.user');
    }
}
