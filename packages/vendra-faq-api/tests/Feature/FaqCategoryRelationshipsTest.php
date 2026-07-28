<?php

declare(strict_types=1);

use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('serves faqs as a to-many relationship of faq categories', function (): void {
    $faqCategory = FaqCategory::factory()->active()->create();
    Faq::factory()->count(2)->active()->forCategory($faqCategory)->create();

    $related = $this->getJson(
        "/v1/faq-categories/{$faqCategory->getKey()}/faqs",
        ['Accept' => 'application/vnd.api+json'],
    );

    $related->assertOk();
    expect($related->json('data'))->toHaveCount(2);

    $relationship = $this->getJson(
        "/v1/faq-categories/{$faqCategory->getKey()}/relationships/faqs",
        ['Accept' => 'application/vnd.api+json'],
    );

    $relationship->assertOk();
    expect($relationship->json('data'))->toHaveCount(2);
});
