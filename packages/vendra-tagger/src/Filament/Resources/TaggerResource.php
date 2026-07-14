<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Filament\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraTagger\Filament\Resources\Pages\CreateTagger;
use Misaf\VendraTagger\Filament\Resources\Pages\EditTagger;
use Misaf\VendraTagger\Filament\Resources\Pages\ListTaggers;
use Misaf\VendraTagger\Filament\Resources\Pages\ViewTagger;
use Misaf\VendraTagger\Filament\Resources\Schemas\TaggerForm;
use Misaf\VendraTagger\Filament\Resources\Tables\TaggerTable;
use Misaf\VendraTagger\Models\Tagger;
use Misaf\VendraTagger\TaggerPlugin;

final class TaggerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Tagger::class;

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $slug = 'taggers';

    public static function getBreadcrumb(): string
    {
        return __('vendra-tagger::navigation.tagger');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-tagger::navigation.tagger');
    }

    public static function getNavigationGroup(): string
    {
        return TaggerPlugin::make()->getNavigationGroup();
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
