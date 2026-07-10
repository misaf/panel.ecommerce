<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraBlog\Filament\Clusters\BlogsCluster;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\CreateBlogPost;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\EditBlogPost;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\ListBlogPosts;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\ViewBlogPost;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Schemas\BlogPostForm;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Tables\BlogPostTable;
use Misaf\VendraBlog\Models\BlogPost;

final class BlogPostResource extends Resource
{
    use Translatable;

    protected static ?string $model = BlogPost::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'blog-posts';

    protected static ?string $cluster = BlogsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-blog::navigation.blog_post');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-blog::navigation.blog_post');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-blog::navigation.blog_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-blog::navigation.blog_post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-blog::navigation.blog_post');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'view'   => ViewBlogPost::route('/{record}'),
            'edit'   => EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return BlogPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogPostTable::configure($table);
    }
}
