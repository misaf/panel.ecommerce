<?php

declare(strict_types=1);

use Misaf\VendraFaq\Database\Factories\FaqCategoryFactory;
use Misaf\VendraFaq\Database\Factories\FaqFactory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('exposes active help blog-posts and topic relationships', function (): void {
    $topic = FaqCategoryFactory::new()->active()->create();
    $article = FaqFactory::new()->forCategory($topic)->active()->create();
    FaqFactory::new()->forCategory($topic)->inactive()->create();

    $this->getJson('/api/content/faqs', ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.id', $article->id)
        ->assertJsonPath('member.0.topic.id', $topic->id);

    $this->getJson("/api/content/faq-categories/{$topic->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('faqs.0.id', $article->id);
});
