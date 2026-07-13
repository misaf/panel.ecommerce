<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraCustomPage\Filament\Clusters\CustomPagesCluster;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\CreateCustomPageCategory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\EditCustomPageCategory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\ListCustomPageCategories;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Pages\ViewCustomPageCategory;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Schemas\CustomPageCategoryForm;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\Tables\CustomPageCategoryTable;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\RelationManagers\CustomPageRelationManager;
use Misaf\VendraCustomPage\Models\CustomPageCategory;

final class CustomPageCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = CustomPageCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

    protected static ?string $cluster = CustomPagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-custom-page::navigation.custom_page_category');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-custom-page::navigation.custom_page_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page_category');
    }

    public static function getRelations(): array
    {
        return [
            CustomPageRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomPageCategories::route('/'),
            'create' => CreateCustomPageCategory::route('/create'),
            'view'   => ViewCustomPageCategory::route('/{record}'),
            'edit'   => EditCustomPageCategory::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return CustomPageCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomPageCategoryTable::configure($table);
    }
}
