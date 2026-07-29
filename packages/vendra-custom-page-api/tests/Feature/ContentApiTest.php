<?php

declare(strict_types=1);

use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('paginates active content pages and exposes their section', function (): void {
    $section = CustomPageCategoryFactory::new()->active()->create();
    $pages = CustomPageFactory::new()->forCategory($section)->active()->count(2)->create();
    $inactivePage = CustomPageFactory::new()->forCategory($section)->inactive()->create();
    $inactiveSection = CustomPageCategoryFactory::new()->inactive()->create();
    $hiddenPage = CustomPageFactory::new()->forCategory($inactiveSection)->active()->create();

    $this->getJson('/api/content/custom-pages?itemsPerPage=1', ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('totalItems', 2)
        ->assertJsonPath('member.0.customPageCategory.id', $section->id)
        ->assertJsonCount(1, 'member');

    $this->getJson("/api/content/custom-pages/{$pages->first()->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonPath('customPageCategory.id', $section->id);

    $this->getJson("/api/content/custom-page-categories/{$section->id}", ['Accept' => 'application/ld+json'])
        ->assertOk()
        ->assertJsonCount(2, 'customPages')
        ->assertJsonMissing(['id' => $inactivePage->id]);

    $this->getJson("/api/content/custom-pages/{$hiddenPage->id}", ['Accept' => 'application/ld+json'])
        ->assertNotFound();
});
