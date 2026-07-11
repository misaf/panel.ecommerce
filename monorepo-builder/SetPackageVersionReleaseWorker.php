<?php

declare(strict_types=1);

namespace Vendra\MonorepoBuilder;

use LogicException;
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

        $rootAndPackageComposerFileInfos = $this->composerJsonProvider->getRootAndPackageFileInfos();
        $rootComposerFileInfo = array_pop($rootAndPackageComposerFileInfos);

        if (null === $rootComposerFileInfo) {
            throw new LogicException('The root composer.json file could not be found.');
        }

        $rootComposerJson = $this->jsonFileManager->loadFromFileInfo($rootComposerFileInfo);
        $rootComposerJson = self::updateRootPackageConstraints($rootComposerJson, $versionInString);

        $this->jsonFileManager->printJsonToFileInfo($rootComposerJson, $rootComposerFileInfo);
    }

    /**
     * @param  array<mixed>  $composerJson
     * @return array<mixed>
     */
    public static function updateRootPackageConstraints(array $composerJson, string $version): array
    {
        $requirements = $composerJson['require'] ?? [];

        if ( ! is_array($requirements)) {
            return $composerJson;
        }

        foreach ($requirements as $package => $constraint) {
            if ( ! is_string($package) || ! str_starts_with($package, 'misaf/vendra-')) {
                continue;
            }

            $requirements[$package] = '^' . $version;
        }

        $composerJson['require'] = $requirements;

        return $composerJson;
    }

    public function getDescription(Version $version): string
    {
        return sprintf('Set each package own "version" field to "%s"', $version->getVersionString());
    }
}
