<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraLanguage\Filament\Clusters\LanguagesCluster;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\CreateLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\EditLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\ListLanguageLines;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Pages\ViewLanguageLine;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Schemas\LanguageLineForm;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Tables\LanguageLineTable;
use Misaf\VendraLanguage\Models\LanguageLine;

final class LanguageLineResource extends Resource
{
    use Translatable;

    protected static ?string $model = LanguageLine::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'language-lines';

    protected static ?string $cluster = LanguagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-language::navigation.language_line');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-language::navigation.language_line');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-language::navigation.language_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-language::navigation.language_line');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-language::navigation.language_line');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLanguageLines::route('/'),
            'create' => CreateLanguageLine::route('/create'),
            'view'   => ViewLanguageLine::route('/{record}'),
            'edit'   => EditLanguageLine::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return LanguageLineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LanguageLineTable::configure($table);
    }
}
