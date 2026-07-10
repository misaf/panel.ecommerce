<?php

declare(strict_types=1);

namespace Misaf\VendraMultimedia\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Misaf\VendraMultimedia\Filament\Resources\Pages\ListMultimedia;
use Misaf\VendraMultimedia\Filament\Resources\Pages\ViewMultimedia;
use Misaf\VendraMultimedia\Filament\Resources\Tables\MultimediaTable;
use Misaf\VendraMultimedia\Models\Multimedia;

final class MultimediaResource extends Resource
{
    protected static ?string $model = Multimedia::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'multimedia';

    public static function getBreadcrumb(): string
    {
        return __('navigation.report_management');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-multimedia::navigation.multimedia');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.report_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-multimedia::navigation.multimedia');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-multimedia::navigation.multimedia');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMultimedia::route('/'),
            'view'  => ViewMultimedia::route('/{record}'),
        ];
    }

    public static function table(Table $table): Table
    {
        return MultimediaTable::configure($table);
    }
}
