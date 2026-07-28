<?php

declare(strict_types=1);

/**
 * @return array<string, list<string>>
 */
function vendraPackageDependencyGraph(): array
{
    $dependencyGraph = [];

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $dependencyGraph[$manifest['name']] = array_values(array_filter(
            array_keys($manifest['require'] ?? []),
            fn(string $package): bool => str_starts_with($package, 'misaf/vendra-'),
        ));
    }

    ksort($dependencyGraph);

    return $dependencyGraph;
}

/**
 * @param  array<string, list<string>>  $dependencyGraph
 * @return list<string>
 */
function reachableVendraPackages(string $package, array $dependencyGraph): array
{
    $reachablePackages = [];
    $pendingPackages = $dependencyGraph[$package] ?? [];

    while ([] !== $pendingPackages) {
        $dependency = array_shift($pendingPackages);

        if (isset($reachablePackages[$dependency])) {
            continue;
        }

        $reachablePackages[$dependency] = true;
        array_push($pendingPackages, ...($dependencyGraph[$dependency] ?? []));
    }

    return array_keys($reachablePackages);
}

it('keeps the Vendra package dependency graph complete and acyclic', function (): void {
    $dependencyGraph = vendraPackageDependencyGraph();
    $knownPackages = array_keys($dependencyGraph);
    $unknownDependencies = [];
    $cycles = [];

    foreach ($dependencyGraph as $package => $dependencies) {
        foreach ($dependencies as $dependency) {
            if ( ! in_array($dependency, $knownPackages, true)) {
                $unknownDependencies[] = "{$package} → {$dependency}";
            }
        }

        if (in_array($package, reachableVendraPackages($package, $dependencyGraph), true)) {
            $cycles[] = $package;
        }
    }

    expect($unknownDependencies)->toBe([])
        ->and($cycles)->toBe([]);
});

it('imports only Vendra namespaces reachable through declared package dependencies', function (): void {
    $dependencyGraph = vendraPackageDependencyGraph();
    $namespacePackages = [];
    $unreachableImports = [];

    foreach (glob(base_path('packages/*/composer.json')) ?: [] as $manifestPath) {
        $manifest = json_decode(
            file_get_contents($manifestPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        foreach (array_keys($manifest['autoload']['psr-4'] ?? []) as $namespace) {
            $namespacePackages[mb_rtrim($namespace, '\\')] = $manifest['name'];
        }
    }

    foreach (array_keys($dependencyGraph) as $package) {
        $packagePath = base_path('packages/' . mb_substr($package, mb_strlen('misaf/')));
        $sourcePath = "{$packagePath}/src";

        if ( ! is_dir($sourcePath)) {
            continue;
        }

        $manifest = json_decode(
            file_get_contents("{$packagePath}/composer.json"),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $reachablePackages = [
            ...reachableVendraPackages($package, $dependencyGraph),
            ...array_values(array_filter(
                array_keys($manifest['suggest'] ?? []),
                fn(string $dependency): bool => str_starts_with($dependency, 'misaf/vendra-'),
            )),
        ];
        $sourceFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($sourceFiles as $sourceFile) {
            if ('php' !== $sourceFile->getExtension()) {
                continue;
            }

            $contents = (string) file_get_contents($sourceFile->getPathname());

            foreach ($namespacePackages as $namespace => $namespacePackage) {
                if ($package === $namespacePackage || in_array($namespacePackage, $reachablePackages, true)) {
                    continue;
                }

                if (1 === preg_match('/^use ' . preg_quote($namespace, '/') . '\\\\/m', $contents)) {
                    $relativePath = mb_substr($sourceFile->getPathname(), mb_strlen(base_path()) + 1);
                    $unreachableImports[] = "{$relativePath} → {$namespacePackage}";
                }
            }
        }
    }

    expect($unreachableImports)->toBe([]);
});
