<?php

declare(strict_types=1);

namespace Vendra\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

final readonly class SetPackageVersionReleaseWorker implements ReleaseWorkerInterface
{
    public function __construct(
        private JsonFileManager $jsonFileManager,
        private ComposerJsonProvider $composerJsonProvider
    ) {}

    public function work(Version $version): void
    {
        $versionInString = $version->getVersionString();

        foreach ($this->composerJsonProvider->getPackagesComposerFileInfos() as $composerFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($composerFileInfo);
            $json['version'] = $versionInString;

            $this->jsonFileManager->printJsonToFileInfo($json, $composerFileInfo);
        }
    }

    public function getDescription(Version $version): string
    {
        return sprintf('Set each package own "version" field to "%s"', $version->getVersionString());
    }
}
