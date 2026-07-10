<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Vendra\MonorepoBuilder\SetPackageVersionReleaseWorker;

require_once __DIR__ . '/monorepo-builder/SetPackageVersionReleaseWorker.php';

return static function (MBConfig $config): void {
    $config->packageDirectories([__DIR__ . '/app-modules']);
    $config->defaultBranch('master');
    $config->workers([
        SetPackageVersionReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
    ]);
};
