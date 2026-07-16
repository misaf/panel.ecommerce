<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Filament\Clusters\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\Pages\ListAuthifyLogs;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\Pages\ViewAuthifyLog;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\Tables\AuthifyLogTable;
use Misaf\VendraAuthifyLog\Models\AuthifyLog;
use Misaf\VendraSupport\Filament\Clusters\SystemCluster;

final class AuthifyLogResource extends Resource
{
    protected static ?string $model = AuthifyLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'authify-logs';

    protected static ?string $cluster = SystemCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_log');
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
