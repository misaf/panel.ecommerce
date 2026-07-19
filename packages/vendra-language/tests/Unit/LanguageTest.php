<?php

declare(strict_types=1);

use Misaf\VendraLanguage\Models\Language;

it('derives its display name from the locale via the ICU catalog', function (): void {
    $language = new Language(['locale' => 'de']);

    expect($language->name)->toBe('German');
});

it('casts is_default to a boolean', function (): void {
    $language = new Language(['locale' => 'en', 'status' => 0, 'is_default' => 1]);

    expect($language->status)->toBeFalse()
        ->and($language->is_default)->toBeTrue();
});
