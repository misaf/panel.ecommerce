<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Misaf\VendraActivityLog\Filament\Resources\Pages\ListActivityLogs;
use Misaf\VendraActivityLog\Filament\Resources\Pages\ViewActivityLog;
use Misaf\VendraActivityLog\Filament\Resources\Tables\ActivityLogTable;
use Misaf\VendraActivityLog\Models\ActivityLog;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'activity-logs';

    public static function getBreadcrumb(): string
    {
        return __('navigation.report_management');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-activity-log::navigation.activity_log');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.report_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-activity-log::navigation.activity_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-activity-log::navigation.activity_log');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view'  => ViewActivityLog::route('/{record}'),
        ];
    }

    public static function table(Table $table): Table
    {
        return ActivityLogTable::configure($table);
    }
}
