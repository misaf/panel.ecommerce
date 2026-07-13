<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\VendraLanguage\Filament\Clusters\LanguagesCluster;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\CreateLanguage;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\EditLanguage;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\ListLanguages;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Pages\ViewLanguage;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Schemas\LanguageForm;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Tables\LanguageTable;
use Misaf\VendraLanguage\Models\Language;

final class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'languages';

    protected static ?string $cluster = LanguagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-language::navigation.language_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLanguages::route('/'),
            'create' => CreateLanguage::route('/create'),
            'view'   => ViewLanguage::route('/{record}'),
            'edit'   => EditLanguage::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return LanguageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LanguageTable::configure($table);
    }
}
