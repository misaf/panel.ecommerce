<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Runtimes;

use Misaf\VendraContainer\ValueObjects\ContainerDefinition;

/**
 * Podman, over its Docker-compatibility socket.
 *
 * The protocol is the same, so the client is not duplicated. Two things are not
 * the same and are handled here:
 *
 * - The compatibility socket serves an older API generation than current Docker,
 *   so the default version differs. Configuration still wins over this.
 * - Podman rejects a create call carrying log options for a driver it is not
 *   using, where Docker would ignore them. Since it logs through k8s-file or
 *   journald rather than json-file, a definition built for Docker's default
 *   would fail here — so options for an unrecognised driver are dropped rather
 *   than passed on, and the container is created with the runtime's own logging.
 *
 * Rootless Podman is the safer of the two runtimes to expose: the socket it
 * serves is not root-equivalent on the host.
 */
final class PodmanRuntime extends DockerCompatibleRuntime
{
    public const string NAME = 'podman';

    public const string DEFAULT_API_VERSION = 'v1.41';

    /**
     * Log drivers Podman accepts options for.
     *
     * @var list<string>
     */
    private const array DRIVERS_ACCEPTING_OPTIONS = ['k8s-file', 'journald', 'json-file'];

    protected function runtimeName(): string
    {
        return self::NAME;
    }

    /**
     * @return array<string, mixed>
     */
    protected function toEnginePayload(ContainerDefinition $definition): array
    {
        $payload = parent::toEnginePayload($definition);
        $driver = $definition->logConfiguration?->driver;

        if (null === $driver || in_array($driver, self::DRIVERS_ACCEPTING_OPTIONS, true)) {
            return $payload;
        }

        if (isset($payload['HostConfig']) && is_array($payload['HostConfig'])) {
            unset($payload['HostConfig']['LogConfig']['Config']);
        }

        return $payload;
    }
}
