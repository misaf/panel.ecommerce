<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ActivityLogs\RelationManagers;

use App\Filament\Admin\Resources\ActivityLogs\ActivityLogs\ActivityLogResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

final class ActivityLogRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function getModelLabel(): string
    {
        return __('navigation.activity_log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.activity_log');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.activity_log');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->activities()->count());
    }

    public function form(Schema $schema): Schema
    {
        return ActivityLogResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return ActivityLogResource::table($table);
    }
}
