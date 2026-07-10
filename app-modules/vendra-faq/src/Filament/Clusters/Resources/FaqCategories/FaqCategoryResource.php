<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraFaq\Filament\Clusters\FaqsCluster;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\CreateFaqCategory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\EditFaqCategory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\ListFaqCategories;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Pages\ViewFaqCategory;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Schemas\FaqCategoryForm;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Tables\FaqCategoryTable;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\RelationManagers\FaqRelationManager;
use Misaf\VendraFaq\Models\FaqCategory;

final class FaqCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = FaqCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

    protected static ?string $cluster = FaqsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-faq::navigation.faq_category');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-faq::navigation.faq_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-faq::navigation.faq_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-faq::navigation.faq_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-faq::navigation.faq_category');
    }

    public static function getRelations(): array
    {
        return [
            FaqRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFaqCategories::route('/'),
            'create' => CreateFaqCategory::route('/create'),
            'view'   => ViewFaqCategory::route('/{record}'),
            'edit'   => EditFaqCategory::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return FaqCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqCategoryTable::configure($table);
    }
}
