<?php

declare(strict_types=1);

use Illuminate\Support\Arr;

it('keeps translation keys aligned across locales', function (string $file): void {
    $basePath = dirname(__DIR__, 2) . '/resources/lang';
    $englishKeys = array_keys(Arr::dot(require $basePath . '/en/' . $file));

    foreach (['de', 'fa'] as $locale) {
        $localizedKeys = array_keys(Arr::dot(require $basePath . '/' . $locale . '/' . $file));

        expect($localizedKeys)->toBe($englishKeys);
    }
})->with(['attributes.php', 'navigation.php', 'tables.php']);
