<?php

declare(strict_types=1);

/**
 * @return array<string, string>
 */
function vendraPackagePaths(): array
{
    $composerFiles = glob(dirname(__DIR__, 2) . '/packages/*/composer.json');

    if (false === $composerFiles) {
        throw new RuntimeException('Unable to discover package composer files.');
    }

    $packages = [];

    foreach ($composerFiles as $composerFile) {
        $packagePath = dirname($composerFile);
        $packages[basename($packagePath)] = $packagePath;
    }

    ksort($packages);

    return $packages;
}

it('ships package-specific Laravel Boost guidelines and one matching skill', function (string $packagePath): void {
    $packageName = basename($packagePath);
    $guidelinePath = $packagePath . '/resources/boost/guidelines/core.blade.php';
    $expectedSkillName = $packageName . '-development';
    $expectedSkillPath = $packagePath . '/resources/boost/skills/' . $expectedSkillName . '/SKILL.md';
    $skillFiles = glob($packagePath . '/resources/boost/skills/*/SKILL.md');

    expect($guidelinePath)->toBeFile()
        ->and($expectedSkillPath)->toBeFile()
        ->and($skillFiles)->toBeArray()->toHaveCount(1);
})->with(fn(): array => vendraPackagePaths());

it('uses valid, focused skill metadata for every package', function (string $packagePath): void {
    $packageName = basename($packagePath);
    $skillName = $packageName . '-development';
    $skillPath = $packagePath . '/resources/boost/skills/' . $skillName . '/SKILL.md';
    $skill = file_get_contents($skillPath);

    if (false === $skill) {
        throw new RuntimeException("Unable to read skill [{$skillPath}].");
    }

    $matches = [];
    $matched = preg_match(
        '/\A---\Rname: (?<name>[a-z0-9-]+)\Rdescription: "(?<description>[^"\r\n]+)"\R---\R/u',
        $skill,
        $matches,
    );

    if (1 !== $matched) {
        throw new RuntimeException("Invalid skill frontmatter in [{$skillPath}].");
    }

    expect($matches['name'])->toBe($skillName)
        ->and($matches['description'])->toContain('packages/' . $packageName)
        ->and($skill)->toContain('# Vendra')
        ->and(str_contains($skill, '`modular`'))->toBeFalse()
        ->and(str_contains($skill, 'creating future'))->toBeFalse();
})->with(fn(): array => vendraPackagePaths());

it('keeps every package guideline focused on its own package', function (string $packagePath): void {
    $packageName = basename($packagePath);
    $composerContents = file_get_contents($packagePath . '/composer.json');
    $guideline = file_get_contents($packagePath . '/resources/boost/guidelines/core.blade.php');

    if (false === $composerContents || false === $guideline) {
        throw new RuntimeException("Unable to read Boost resources for [{$packageName}].");
    }

    $composer = json_decode(
        $composerContents,
        associative: true,
        flags: JSON_THROW_ON_ERROR,
    );

    if ( ! is_array($composer) || ! isset($composer['name']) || ! is_string($composer['name'])) {
        throw new RuntimeException("Invalid package metadata for [{$packageName}].");
    }

    expect($composer['name'])->toBe('misaf/' . $packageName)
        ->and($guideline)
        ->toContain('`misaf/' . $packageName . '`', '`packages/' . $packageName . '`');
})->with(fn(): array => vendraPackagePaths());
