<?php

declare(strict_types=1);

use Misaf\VendraBlog\Database\Factories\BlogPostCategoryFactory;
use Misaf\VendraBlog\Database\Factories\BlogPostFactory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('exposes active blog-posts with their section relationship', function (): void {
    $section = BlogPostCategoryFactory::new()->active()->create();
    $article = BlogPostFactory::new()->forCategory($section)->active()->create();
    BlogPostFactory::new()->forCategory($section)->inactive()->create();

    $this->getJson('/api/content/blog-posts', ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.id', $article->id)
        ->assertJsonPath('member.0.section.id', $section->id);

    $this->getJson("/api/content/blog-post-categories/{$section->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('blogPosts.0.id', $article->id);
});
