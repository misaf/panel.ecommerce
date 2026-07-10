<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Misaf\VendraAuthifyLog\Filament\Resources\Pages\ListAuthifyLogs;
use Misaf\VendraAuthifyLog\Filament\Resources\Pages\ViewAuthifyLog;
use Misaf\VendraAuthifyLog\Filament\Resources\Tables\AuthifyLogTable;
use Misaf\VendraAuthifyLog\Models\AuthifyLog;

final class AuthifyLogResource extends Resource
{
    protected static ?string $model = AuthifyLog::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'authify-logs';

    public static function getBreadcrumb(): string
    {
        return __('navigation.report_management');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.report_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAuthifyLogs::route('/'),
            'view'   => ViewAuthifyLog::route('/{record}'),
        ];
    }

    public static function table(Table $table): Table
    {
        return AuthifyLogTable::configure($table);
    }
}
