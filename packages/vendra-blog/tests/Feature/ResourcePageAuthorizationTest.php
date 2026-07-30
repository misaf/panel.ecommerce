<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraBlog\Database\Factories\BlogPostCategoryFactory;
use Misaf\VendraBlog\Database\Factories\BlogPostFactory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\CreateBlogPostCategory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\ListBlogPostCategories;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\CreateBlogPost;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\EditBlogPost;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\ListBlogPosts;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\ViewBlogPost;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('renders the create blog post page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateBlogPost::class)
        ->assertOk();
});

it('renders the edit blog post page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $blogPost = BlogPostFactory::new()->createOne();

    livewire(EditBlogPost::class, ['record' => $blogPost->getKey()])
        ->assertOk();
});

it('renders the view blog post page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $blogPost = BlogPostFactory::new()->createOne();

    livewire(ViewBlogPost::class, ['record' => $blogPost->getKey()])
        ->assertOk();
});

it('renders the create blog post category page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateBlogPostCategory::class)
        ->assertOk();
});

it('renders the reorderable blog posts table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $blogPost = BlogPostFactory::new()->createOne();

    livewire(ListBlogPosts::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$blogPost]);
});

it('renders the reorderable blog post categories table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $blogPostCategory = BlogPostCategoryFactory::new()->createOne();

    livewire(ListBlogPostCategories::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$blogPostCategory]);
});
