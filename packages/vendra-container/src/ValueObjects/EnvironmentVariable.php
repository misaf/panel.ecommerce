<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;
use Stringable;

/**
 * One environment entry, kept as a pair until the adapter flattens it.
 *
 * The Engine wants "KEY=VALUE" strings; callers think in keys and values.
 * Converting at the boundary rather than in the caller means a value containing
 * an "=" cannot silently become part of the name.
 */
final class EnvironmentVariable implements Stringable
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
    ) {
        if ('' === mb_trim($name)) {
            throw new InvalidArgumentException('An environment variable name is required.');
        }
    }

    /**
     * @param  array<string, string|int|float|bool|null> $variables
     * @return list<self>
     */
    public static function collection(array $variables): array
    {
        $collection = [];

        foreach ($variables as $name => $value) {
            $collection[] = new self($name, match (true) {
                null === $value => '',
                is_bool($value) => $value ? 'true' : 'false',
                default         => (string) $value,
            });
        }

        return $collection;
    }

    public function __toString(): string
    {
        return $this->name . '=' . $this->value;
    }
}
