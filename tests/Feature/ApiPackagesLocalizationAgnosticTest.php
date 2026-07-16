<?php

declare(strict_types=1);

use Illuminate\Support\Str;

it('keeps every domain api package independent from vendra localization', function (): void {
    $apiPackages = collect(glob(base_path('packages/vendra-*-api'), GLOB_ONLYDIR));

    expect($apiPackages)->not->toBeEmpty();

    $apiPackages->each(function (string $packagePath): void {
        $composer = json_decode(
            file_get_contents($packagePath . '/composer.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        expect($composer['require'] ?? [])
            ->not->toHaveKey(
                'misaf/vendra-localization',
                class_basename($packagePath) . ' must not require vendra-localization.',
            );

        $routePath = $packagePath . '/routes/api.php';

        if (is_file($routePath)) {
            expect(Str::contains(file_get_contents($routePath), 'vendra.locale'))
                ->toBeFalse(class_basename($packagePath) . ' must not attach vendra.locale middleware.');
        }
    });
});
