<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraTagger\Filament\Resources\Pages\CreateTagger;
use Misaf\VendraTagger\Filament\Resources\Pages\EditTagger;
use Misaf\VendraTagger\Filament\Resources\Pages\ListTaggers;
use Misaf\VendraTagger\Filament\Resources\Pages\ViewTagger;
use Misaf\VendraTagger\Filament\Resources\Schemas\TaggerForm;
use Misaf\VendraTagger\Filament\Resources\Tables\TaggerTable;
use Misaf\VendraTagger\Models\Tagger;

final class TaggerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Tagger::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $slug = 'taggers';

    public static function getBreadcrumb(): string
    {
        return __('navigation.content_management');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-tagger::navigation.tagger');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-tagger::navigation.tagger');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-tagger::navigation.tagger');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTaggers::route('/'),
            'create' => CreateTagger::route('/create'),
            'view'   => ViewTagger::route('/{record}'),
            'edit'   => EditTagger::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return TaggerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaggerTable::configure($table);
    }
}
