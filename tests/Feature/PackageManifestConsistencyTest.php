<?php

declare(strict_types=1);

it('keeps package manifest metadata consistent', function (): void {
    $manifestPaths = glob(base_path('packages/*/composer.json')) ?: [];

    expect($manifestPaths)->not->toBeEmpty();

    foreach ($manifestPaths as $manifestPath) {
        $package = basename(dirname($manifestPath));
        $repository = "https://github.com/misaf/{$package}";
        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        expect($manifest)
            ->name->toBe("misaf/{$package}")
            ->type->toBe('vendra-module')
            ->license->toBe('MIT')
            ->keywords->not->toBeEmpty()
            ->authors->toBe([[
                'name'  => 'Ehsan Mahmoodi',
                'email' => 'misaf.1990@gmail.com',
                'role'  => 'Developer',
            ]])
            ->homepage->toBe($repository)
            ->support->toBe([
                'issues' => "{$repository}/issues",
                'source' => $repository,
            ]);
    }
});

it('keeps package-only namespaces out of production autoloading', function (): void {
    $rootManifest = json_decode(
        file_get_contents(base_path('composer.json')),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    expect(array_values($rootManifest['autoload']['psr-4']))
        ->each->not->toStartWith('packages/');

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $productionPaths = array_values($manifest['autoload']['psr-4'] ?? []);
        $developmentPaths = array_values($manifest['autoload-dev']['psr-4'] ?? []);

        expect($productionPaths)
            ->not->toContain('database/factories/')
            ->not->toContain('tests/')
            ->and($developmentPaths)
            ->toContain('tests/');

        if (is_dir(dirname($manifestPath) . '/database/factories')) {
            expect($developmentPaths)->toContain('database/factories/');
        }
    }
});

it('does not keep package-level Composer lock files', function (): void {
    expect(array_values(glob(base_path('packages/*/composer.lock')) ?: []))->toBe([]);
});
