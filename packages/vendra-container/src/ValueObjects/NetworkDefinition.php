<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * A container network to create.
 */
final class NetworkDefinition
{
    /**
     * @param array<string, string> $labels
     */
    public function __construct(
        public readonly string $name,
        public readonly string $driver = 'bridge',
        public readonly bool $attachable = true,
        public readonly bool $internal = false,
        public readonly array $labels = [],
    ) {
        if ('' === mb_trim($name)) {
            throw new InvalidArgumentException('A network name is required.');
        }
    }
}
