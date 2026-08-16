<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Exceptions;

/**
 * The configured endpoint did not answer, or answered but refused the API
 * version every other call uses.
 *
 * The two are distinguished deliberately: one is a wrong socket path or mount,
 * the other a wrong CONTAINER_API_VERSION, and an operator fixes them in
 * different places.
 */
final class RuntimeUnreachableException extends ContainerRuntimeException
{
    public static function endpoint(string $endpoint, string $reason): self
    {
        return new self("The container runtime endpoint [{$endpoint}] is not reachable: {$reason}");
    }

    public static function apiVersion(string $endpoint, string $apiVersion, string $reason): self
    {
        return new self(sprintf(
            'The container runtime at [%s] is reachable but rejected API version [%s]: %s. '
            . 'Set CONTAINER_API_VERSION to a version it serves.',
            $endpoint,
            $apiVersion,
            $reason,
        ));
    }

    public static function unconfigured(): self
    {
        return new self('No container runtime endpoint is configured. Set CONTAINER_ENDPOINT first.');
    }
}
