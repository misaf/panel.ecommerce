<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Exceptions;

use Misaf\VendraContainer\ValueObjects\ContainerId;

/**
 * The runtime has no container by that id or name.
 *
 * Separate from the general failure so a caller can tell "not there" from "the
 * runtime refused"; `remove()` treats it as success, `inspect()` throws it.
 */
final class ContainerNotFoundException extends ContainerRuntimeException
{
    public static function for(ContainerId $container): self
    {
        return new self("No container named [{$container}] exists.");
    }
}
