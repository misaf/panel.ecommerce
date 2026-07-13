<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraCustomPage\Filament\Clusters\CustomPagesCluster;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\CreateCustomPage;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\EditCustomPage;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\ListCustomPages;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Pages\ViewCustomPage;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Schemas\CustomPageForm;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Tables\CustomPageTable;
use Misaf\VendraCustomPage\Models\CustomPage;

final class CustomPageResource extends Resource
{
    use Translatable;

    protected static ?string $model = CustomPage::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'custom-pages';

    protected static ?string $cluster = CustomPagesCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-custom-page::navigation.custom_page_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomPages::route('/'),
            'create' => CreateCustomPage::route('/create'),
            'view'   => ViewCustomPage::route('/{record}'),
            'edit'   => EditCustomPage::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return CustomPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomPageTable::configure($table);
    }
}
