<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\CreateBlogPostCategory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\EditBlogPostCategory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\ListBlogPostCategories;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\ViewBlogPostCategory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Schemas\BlogPostCategoryForm;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Tables\BlogPostCategoryTable;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\RelationManagers\BlogPostRelationManager;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraSupport\Filament\Clusters\ContentCluster;

final class BlogPostCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = BlogPostCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'blog-post-categories';

    protected static ?string $cluster = ContentCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-blog::navigation.blog_post_category');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-blog::navigation.blog_post_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-blog::navigation.blog_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-blog::navigation.blog_post_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-blog::navigation.blog_post_category');
    }

    public static function getRelations(): array
    {
        return [
            BlogPostRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBlogPostCategories::route('/'),
            'create' => CreateBlogPostCategory::route('/create'),
            'view'   => ViewBlogPostCategory::route('/{record}'),
            'edit'   => EditBlogPostCategory::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return BlogPostCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogPostCategoryTable::configure($table);
    }
}
