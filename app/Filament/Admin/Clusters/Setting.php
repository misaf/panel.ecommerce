<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

final class Setting extends Cluster
{
    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'settings';

    public static function getNavigationGroup(): ?string
    {
        return __('model.setting');
    }

    public static function getNavigationLabel(): string
    {
        return __('page.configuration');
    }
}
