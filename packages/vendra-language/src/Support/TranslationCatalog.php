<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Symfony\Component\Finder\SplFileInfo;

final readonly class TranslationCatalog
{
    public function __construct(
        private Filesystem $files,
        private Translator $translator,
    ) {}

    /**
     * @return array<string, string>
     */
    public function namespaceOptions(): array
    {
        $loader = $this->fileLoader();

        if (null === $loader) {
            return [];
        }

        $namespaces = array_filter(
            array_keys($loader->namespaces()),
            fn(string $namespace): bool => Str::startsWith($namespace, 'vendra-'),
        );

        return $this->options($namespaces);
    }

    /**
     * @return array<string, string>
     */
    public function groupOptions(?string $namespace): array
    {
        $groups = [];

        foreach ($this->translationFiles($namespace) as $file) {
            $groups[] = $this->groupFromFile($file);
        }

        return $this->options($groups);
    }

    /**
     * @return array<string, string>
     */
    public function keyOptions(?string $namespace, ?string $group): array
    {
        if (null === $group || ! array_key_exists($group, $this->groupOptions($namespace))) {
            return [];
        }

        $keys = [];

        foreach ($this->translationFiles($namespace) as $file) {
            if ($this->groupFromFile($file) !== $group) {
                continue;
            }

            $translations = $this->files->getRequire($file->getRealPath());

            if (is_array($translations)) {
                $keys = [...$keys, ...array_keys(Arr::dot($translations))];
            }
        }

        return $this->options($keys);
    }

    private function fileLoader(): ?FileLoader
    {
        $loader = $this->translator->getLoader();

        return $loader instanceof FileLoader ? $loader : null;
    }

    /**
     * @return list<string>
     */
    private function paths(?string $namespace): array
    {
        $loader = $this->fileLoader();

        if (null === $loader) {
            return [];
        }

        if (null === $namespace) {
            return array_values(array_filter(
                $loader->paths(),
                is_string(...),
            ));
        }

        if ( ! Str::startsWith($namespace, 'vendra-')) {
            return [];
        }

        $path = $loader->namespaces()[$namespace] ?? null;

        return is_string($path) ? [$path] : [];
    }

    /**
     * @return list<SplFileInfo>
     */
    private function translationFiles(?string $namespace): array
    {
        $translationFiles = [];

        foreach ($this->paths($namespace) as $path) {
            if ( ! $this->files->isDirectory($path)) {
                continue;
            }

            foreach ($this->files->allFiles($path) as $file) {
                if ('php' === $file->getExtension() && Str::contains($file->getRelativePathname(), DIRECTORY_SEPARATOR)) {
                    $translationFiles[] = $file;
                }
            }
        }

        return $translationFiles;
    }

    private function groupFromFile(SplFileInfo $file): string
    {
        return Str::of($file->getRelativePathname())
            ->after(DIRECTORY_SEPARATOR)
            ->beforeLast('.php')
            ->replace(DIRECTORY_SEPARATOR, '/')
            ->toString();
    }

    /**
     * @param array<int, string> $values
     *
     * @return array<string, string>
     */
    private function options(array $values): array
    {
        $values = array_values(array_unique($values));
        sort($values);

        return array_combine($values, $values);
    }
}
