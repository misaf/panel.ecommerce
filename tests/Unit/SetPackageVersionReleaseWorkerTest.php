<?php

declare(strict_types=1);

use Vendra\MonorepoBuilder\SetPackageVersionReleaseWorker;

require_once dirname(__DIR__, 2) . '/monorepo-builder/SetPackageVersionReleaseWorker.php';

it('updates root Vendra package constraints for a release', function (): void {
    $composerJson = [
        'require' => [
            'php'                       => '^8.4',
            'misaf/vendra-support'      => '^0.0.4',
            'misaf/vendra-user'         => '^0.0.4',
            'misaf/laravel-authify-log' => '^1.0',
        ],
        'require-dev' => [
            'misaf/vendra-testing' => '^0.0.4',
        ],
    ];

    $updatedComposerJson = SetPackageVersionReleaseWorker::updateRootPackageConstraints(
        $composerJson,
        '0.0.5',
    );

    expect($updatedComposerJson['require'])
        ->toMatchArray([
            'php'                       => '^8.4',
            'misaf/vendra-support'      => '^0.0.5',
            'misaf/vendra-user'         => '^0.0.5',
            'misaf/laravel-authify-log' => '^1.0',
        ])
        ->and($updatedComposerJson['require-dev'])
        ->toBe([
            'misaf/vendra-testing' => '^0.0.4',
        ]);
});
