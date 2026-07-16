<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('stores package resources according to their cluster assignment', function (): void {
    $resourceFiles = collect(File::allFiles(base_path('packages')))
        ->filter(fn(SplFileInfo $file): bool => Str::contains(
            $file->getPathname(),
            DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR,
        ))
        ->filter(fn(SplFileInfo $file): bool => Str::endsWith($file->getFilename(), 'Resource.php'));

    expect($resourceFiles)->not->toBeEmpty();

    foreach ($resourceFiles as $resourceFile) {
        $path = $resourceFile->getPathname();
        $contents = File::get($path);
        $hasCluster = 1 === preg_match('/protected static [^;]+\\$cluster\\s*=/', $contents);
        $expectedDirectory = $hasCluster
            ? DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Clusters' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR
            : DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR;

        expect($path)->toContain($expectedDirectory);
    }
});
