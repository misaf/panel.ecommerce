<?php

declare(strict_types=1);

use Misaf\VendraFaq\Models\Faq;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('generates translatable slugs from the name when none is provided', function (): void {
    $faq = Faq::factory()->create([
        'name' => ['en' => 'Hello World'],
        'slug' => null,
    ]);

    expect($faq->getTranslation('slug', 'en'))->toBe('hello-world');
});

it('keeps a manually provided slug', function (): void {
    $faq = Faq::factory()->create([
        'slug' => ['en' => 'custom-slug'],
    ]);

    expect($faq->getTranslation('slug', 'en'))->toBe('custom-slug');
});

it('does not overwrite an existing slug when the name changes', function (): void {
    $faq = Faq::factory()->create([
        'name' => ['en' => 'Hello World'],
        'slug' => null,
    ]);

    $faq->update(['name' => ['en' => 'Changed Name']]);

    expect($faq->refresh()->getTranslation('slug', 'en'))->toBe('hello-world');
});
