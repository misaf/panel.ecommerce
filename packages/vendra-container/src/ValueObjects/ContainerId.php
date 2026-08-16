<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;
use Stringable;

/**
 * The handle a runtime addresses one container by.
 *
 * Engine-compatible runtimes accept a name or an id interchangeably on every
 * container endpoint, so this carries whichever the caller holds rather than
 * forcing a lookup. Naming it at all is what keeps `remove($container)` from
 * being another bare string among the image references and network names.
 */
final class ContainerId implements Stringable
{
    public function __construct(public readonly string $value)
    {
        if ('' === mb_trim($value)) {
            throw new InvalidArgumentException('A container id or name is required.');
        }
    }

    public static function fromName(string $name): self
    {
        return new self($name);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
