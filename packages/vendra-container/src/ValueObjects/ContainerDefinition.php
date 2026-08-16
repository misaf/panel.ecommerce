<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * Everything needed to create one container, as a typed value.
 *
 * This is the whole vocabulary the layers above the runtime speak: they describe
 * *what* should run, and the adapter decides how its runtime is told to run it.
 * A caller that had to assemble an Engine create payload would be coupled to
 * Docker's JSON shape, which is exactly the knowledge this package exists to
 * hold.
 */
final class ContainerDefinition
{
    /**
     * @param list<EnvironmentVariable> $environment
     * @param array<string, string>     $labels
     * @param list<PortBinding>         $ports
     * @param list<VolumeMount>         $volumes
     * @param list<string>              $networks      networks to attach on creation
     * @param list<string>              $securityOptions
     * @param list<string>|null         $command       overrides the image's command
     */
    public function __construct(
        public readonly string $name,
        public readonly ImageReference $image,
        public readonly array $environment = [],
        public readonly array $labels = [],
        public readonly array $ports = [],
        public readonly array $volumes = [],
        public readonly array $networks = [],
        public readonly ?HealthCheck $healthCheck = null,
        public readonly ?RestartPolicy $restartPolicy = null,
        public readonly ?LogConfiguration $logConfiguration = null,
        public readonly array $securityOptions = [],
        public readonly ?array $command = null,
    ) {
        if ('' === mb_trim($name)) {
            throw new InvalidArgumentException('A container name is required.');
        }
    }

    public function id(): ContainerId
    {
        return new ContainerId($this->name);
    }

    /**
     * The network the container is attached to first, if any.
     *
     * Engine-compatible runtimes take one network at creation time and treat the
     * rest as later attachments, so the primary one is named rather than left to
     * array ordering at the call site.
     */
    public function primaryNetwork(): ?string
    {
        return $this->networks[0] ?? null;
    }

    public function expectsHealthChecks(): bool
    {
        return null !== $this->healthCheck;
    }
}
