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

it('keeps package test suites tenant-provider agnostic', function (): void {
    $offending = [];

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $packagePath = dirname($manifestPath);

        if (in_array(basename($packagePath), ['vendra-tenant', 'vendra-subscription'], true)) {
            continue;
        }

        foreach (glob("{$packagePath}/tests/{,*/,*/*/}*.php", GLOB_BRACE) ?: [] as $testFile) {
            $importsTenant = 1 === preg_match(
                '/^use Misaf\\\\VendraTenant\\\\/m',
                (string) file_get_contents($testFile),
            );

            if ($importsTenant) {
                $offending[] = basename($packagePath) . '/' . basename($testFile);
            }
        }
    }

    expect($offending)->toBe([]);
});

it('declares vendra-user in every package whose tests import it', function (): void {
    $undeclared = [];

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $packagePath = dirname($manifestPath);

        if ('vendra-user' === basename($packagePath)) {
            continue;
        }

        $testFiles = glob("{$packagePath}/tests/{,*/,*/*/}*.php", GLOB_BRACE) ?: [];

        $importsUser = array_any(
            $testFiles,
            fn(string $testFile): bool => 1 === preg_match(
                '/^use Misaf\\\\VendraUser\\\\/m',
                (string) file_get_contents($testFile),
            ),
        );

        if ( ! $importsUser) {
            continue;
        }

        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $declared = ($manifest['require'] ?? []) + ($manifest['require-dev'] ?? []);

        if ( ! array_key_exists('misaf/vendra-user', $declared)) {
            $undeclared[] = basename($packagePath);
        }
    }

    expect($undeclared)->toBe([]);
});

it('requires vendra-multimedia in every package whose src uses the Spatie media surface', function (): void {
    $undeclared = [];

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $packagePath = dirname($manifestPath);
        $sourcePath = "{$packagePath}/src";

        if ('vendra-multimedia' === basename($packagePath) || ! is_dir($sourcePath)) {
            continue;
        }

        $usesSpatieMediaSurface = false;
        $sourceFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($sourceFiles as $sourceFile) {
            if ('php' !== $sourceFile->getExtension()) {
                continue;
            }

            $matchesSurface = preg_match(
                '/^use (?:Spatie\\\\MediaLibrary\\\\|Filament\\\\[\w\\\\]+\\\\SpatieMediaLibrary)/m',
                (string) file_get_contents($sourceFile->getPathname()),
            );

            if (1 === $matchesSurface) {
                $usesSpatieMediaSurface = true;

                break;
            }
        }

        if ( ! $usesSpatieMediaSurface) {
            continue;
        }

        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        if ( ! array_key_exists('misaf/vendra-multimedia', $manifest['require'] ?? [])) {
            $undeclared[] = basename($packagePath);
        }
    }

    expect($undeclared)->toBe([]);
});

it('does not keep package-level Composer lock files', function (): void {
    expect(array_values(glob(base_path('packages/*/composer.lock')) ?: []))->toBe([]);
});
