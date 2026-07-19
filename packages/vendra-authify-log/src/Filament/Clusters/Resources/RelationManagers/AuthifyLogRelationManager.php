<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Filament\Clusters\Resources\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\AuthifyLogResource;
use Misaf\VendraAuthifyLog\Models\AuthifyLog;
use Misaf\VendraUser\Models\User;

final class AuthifyLogRelationManager extends RelationManager
{
    protected static string $relationship = 'authifyLogs';

    protected static bool $isBadgeDeferred = true;

    public static function getModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_logs');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-authify-log::navigation.authify_logs');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-authify-log::navigation.authify_log');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        if ( ! $ownerRecord instanceof User) {
            return (string) Number::format(0);
        }

        return (string) Number::format(
            AuthifyLog::query()
                ->where('user_id', $ownerRecord->getKey())
                ->count(),
        );
    }

    public function table(Table $table): Table
    {
        return AuthifyLogResource::table($table);
    }
}
