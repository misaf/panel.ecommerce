<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'logs/activities';

    public static function getModelLabel(): string
    {
        return __('model.activity_log');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.report');
    }

    public static function getNavigationLabel(): string
    {
        return __('model.activity_log');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function getpluralModelLabel(): string
    {
        return __('model.activity_log');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name'),
            ])
            ->filters([

            ]);
    }
}
