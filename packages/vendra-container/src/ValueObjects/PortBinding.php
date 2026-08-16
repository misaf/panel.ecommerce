<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * A port the container exposes, optionally published on the host.
 *
 * A binding with no host port is exposed to the container network only, which is
 * how a container behind a reverse proxy is normally reached — publishing it on
 * the host as well would make it reachable around the proxy.
 */
final class PortBinding
{
    public function __construct(
        public readonly int $containerPort,
        public readonly ?int $hostPort = null,
        public readonly string $protocol = 'tcp',
        public readonly ?string $hostIp = null,
    ) {
        if ($containerPort < 1 || $containerPort > 65535) {
            throw new InvalidArgumentException("Invalid container port [{$containerPort}].");
        }

        if ('tcp' !== $protocol && 'udp' !== $protocol) {
            throw new InvalidArgumentException("Invalid port protocol [{$protocol}].");
        }
    }

    /**
     * The Engine's port key, e.g. "3000/tcp".
     */
    public function key(): string
    {
        return $this->containerPort . '/' . $this->protocol;
    }

    public function isPublished(): bool
    {
        return null !== $this->hostPort;
    }
}
