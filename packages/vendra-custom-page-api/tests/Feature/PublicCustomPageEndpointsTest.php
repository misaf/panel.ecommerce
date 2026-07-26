<?php

declare(strict_types=1);

use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;

beforeEach(function (): void {
    forgetCurrentTestTenant();
    makeCurrentTestTenant();
    app()->setLocale('en');
});

afterEach(function (): void {
    forgetCurrentTestTenant();
});

it('filters localized custom page attributes and includes their category', function (): void {
    $category = CustomPageCategory::factory()->enabled()->create([
        'name' => ['en' => 'Documentation'],
    ]);

    $matchingPage = CustomPage::factory()->enabled()->forCategory($category)->create([
        'name' => ['en' => 'Getting Started'],
    ]);

    CustomPage::factory()->disabled()->forCategory($category)->create([
        'name' => ['en' => 'Internal Draft'],
    ]);

    $this->getJson(
        '/v1/custom-pages?filter[name]=Getting&filter[active]=true&include=customPageCategory',
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $matchingPage->getKey())
        ->assertJsonPath('data.0.attributes.name.en', 'Getting Started')
        ->assertJsonPath('data.0.relationships.customPageCategory.data.id', (string) $category->getKey())
        ->assertJsonCount(1, 'included')
        ->assertJsonPath('included.0.attributes.name.en', 'Documentation');
});

it('supports the soft-delete filters declared by both schemas', function (): void {
    $activeCategory = CustomPageCategory::factory()->enabled()->create();
    $trashedCategory = CustomPageCategory::factory()->enabled()->create();
    $trashedCategory->delete();

    $activePage = CustomPage::factory()->enabled()->forCategory($activeCategory)->create();
    $trashedPage = CustomPage::factory()->enabled()->forCategory($activeCategory)->create();
    $trashedPage->delete();

    $this->getJson('/v1/custom-page-categories?filter[only-trashed]=true', [
        'Accept' => 'application/vnd.api+json',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $trashedCategory->getKey());

    $this->getJson('/v1/custom-pages?filter[with-trashed]=true', [
        'Accept' => 'application/vnd.api+json',
    ])
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson('/v1/custom-pages?filter[only-trashed]=true', [
        'Accept' => 'application/vnd.api+json',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $trashedPage->getKey());

    expect($activePage->exists)->toBeTrue();
});

it('rejects unsupported or invalid pagination parameters', function (): void {
    $page = CustomPage::factory()->enabled()->create();

    $this->getJson('/v1/custom-pages?page[size]=0', ['Accept' => 'application/vnd.api+json'])
        ->assertStatus(400);

    $this->getJson(
        '/v1/custom-pages/' . $page->getKey() . '?page[number]=1',
        ['Accept' => 'application/vnd.api+json'],
    )->assertStatus(400);
});
