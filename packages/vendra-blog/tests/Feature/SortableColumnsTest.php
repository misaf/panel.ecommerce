<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraBlog\Database\Factories\BlogPostCategoryFactory;
use Misaf\VendraBlog\Database\Factories\BlogPostFactory;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\Pages\ListBlogPostCategories;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\Pages\ListBlogPosts;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('sorts the blog posts table by every sortable column following the stored values', function (): void {
    $blogPostCategory = BlogPostCategoryFactory::new()->createOne();

    $first = BlogPostFactory::new()->forCategory($blogPostCategory)->createOne();
    $second = BlogPostFactory::new()->forCategory($blogPostCategory)->createOne();

    expect(livewire(ListBlogPosts::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});

it('sorts the blog post categories table by every sortable column following the stored values', function (): void {
    $first = BlogPostCategoryFactory::new()->createOne();
    $second = BlogPostCategoryFactory::new()->createOne();

    expect(livewire(ListBlogPostCategories::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
