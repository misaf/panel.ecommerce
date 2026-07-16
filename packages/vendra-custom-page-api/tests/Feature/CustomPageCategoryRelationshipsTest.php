<?php

declare(strict_types=1);

use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::forgetCurrent();
    Tenant::factory()->enabled()->create()->makeCurrent();
});

afterEach(function (): void {
    Tenant::forgetCurrent();
});

it('serves custom pages as a to-many relationship of categories', function (): void {
    $category = CustomPageCategory::factory()->enabled()->create();
    CustomPage::factory()->count(2)->enabled()->forCategory($category)->create();

    $related = $this->getJson(
        "/v1/custom-page-categories/{$category->getKey()}/custom-pages",
        ['Accept' => 'application/vnd.api+json'],
    );

    $related->assertOk();
    expect($related->json('data'))->toHaveCount(2);

    $relationship = $this->getJson(
        "/v1/custom-page-categories/{$category->getKey()}/relationships/custom-pages",
        ['Accept' => 'application/vnd.api+json'],
    );

    $relationship->assertOk();
    expect($relationship->json('data'))->toHaveCount(2);
});
