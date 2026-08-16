<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Exceptions;

use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use RuntimeException;

/**
 * Every failure this package reports, normalised to one type.
 *
 * Callers above the runtime cannot tell a Docker error body from a Podman one
 * and should not have to: they catch this and get a message an operator can act
 * on, whichever daemon produced it.
 */
class ContainerRuntimeException extends RuntimeException
{
    public static function create(ContainerId $container, string $reason): self
    {
        return new self("Creating container [{$container}] failed: {$reason}");
    }

    public static function start(ContainerId $container, string $reason): self
    {
        return new self("Starting container [{$container}] failed: {$reason}");
    }

    public static function stop(ContainerId $container, string $reason): self
    {
        return new self("Stopping container [{$container}] failed: {$reason}");
    }

    public static function restart(ContainerId $container, string $reason): self
    {
        return new self("Restarting container [{$container}] failed: {$reason}");
    }

    public static function remove(ContainerId $container, string $reason): self
    {
        return new self("Removing container [{$container}] failed: {$reason}");
    }

    public static function logs(ContainerId $container, string $reason): self
    {
        return new self("Reading logs for container [{$container}] failed: {$reason}");
    }

    public static function pull(ImageReference $image, string $reason): self
    {
        return new self("Pulling [{$image}] failed: {$reason}");
    }

    public static function network(string $name, string $reason): self
    {
        return new self("Creating network [{$name}] failed: {$reason}");
    }
}
