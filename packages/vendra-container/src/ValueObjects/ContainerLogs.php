<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Stringable;

/**
 * A container's captured output.
 */
final class ContainerLogs implements Stringable
{
    public function __construct(
        public readonly ContainerId $container,
        public readonly string $output,
    ) {}

    public function __toString(): string
    {
        return $this->output;
    }

    /**
     * @return list<string>
     */
    public function lines(): array
    {
        if ('' === mb_trim($this->output)) {
            return [];
        }

        return array_values(array_filter(
            preg_split('/\r?\n/', $this->output) ?: [],
            static fn(string $line): bool => '' !== mb_trim($line),
        ));
    }

    public function isEmpty(): bool
    {
        return [] === $this->lines();
    }
}
