<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers faq api read routes', function (): void {
    expect(Route::has('vendra-faq.faqs.index'))->toBeTrue()
        ->and(Route::has('vendra-faq.faq-categories.index'))->toBeTrue()
        ->and(route('vendra-faq.faqs.index', [], false))->toBe('/v1/faqs')
        ->and(route('vendra-faq.faq-categories.index', [], false))->toBe('/v1/faq-categories');
});
