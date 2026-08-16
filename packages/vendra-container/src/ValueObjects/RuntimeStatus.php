<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Misaf\VendraContainer\Enums\ContainerEngine;

/**
 * The answer to "is there a container runtime there, and what is it?".
 *
 * Callers use this to decide whether to attempt work at all. They should not use
 * `$runtime` to branch on behaviour — every difference that matters is already
 * absorbed by the adapter — but naming the runtime makes an operator-facing
 * error say which daemon did not answer.
 *
 * `$runtime` is the configured name and `$version` is what the daemon called
 * itself, which are not the same claim and can disagree: one endpoint can be
 * moved between daemons without the configuration following it. That
 * disagreement is reported rather than resolved — see {@see engineMismatch()} —
 * because the adapters are interchangeable enough that it is a warning, not a
 * failure.
 */
final class RuntimeStatus
{
    public function __construct(
        public readonly bool $reachable,
        public readonly string $runtime,
        public readonly string $apiVersion,
        public readonly ?string $version = null,
        public readonly ?string $message = null,
        public readonly ?string $endpoint = null,
    ) {}

    public static function reachable(
        string $runtime,
        string $apiVersion,
        ?string $version = null,
        ?string $endpoint = null,
    ): self {
        return new self(true, $runtime, $apiVersion, $version, endpoint: $endpoint);
    }

    public static function unreachable(
        string $runtime,
        string $apiVersion,
        string $message,
        ?string $endpoint = null,
    ): self {
        return new self(false, $runtime, $apiVersion, message: $message, endpoint: $endpoint);
    }

    /**
     * Which engine the daemon says it is, from its own `Server` header.
     *
     * Docker answers "Docker/<version>" and Podman's compatibility socket
     * answers "Libpod/<version>", so the header identifies the engine where the
     * configuration only states an intent. Null means it named itself as
     * neither, which is not an error: an unrecognised header is no evidence of a
     * mismatch.
     */
    public function reportedEngine(): ?ContainerEngine
    {
        return ContainerEngine::fromServerHeader($this->version);
    }

    /**
     * Whether the daemon that answered is not the runtime that was configured.
     *
     * Both adapters speak the same API, so this costs nothing until something
     * daemon-scoped is missing — a network, an image, a container placed on the
     * other one. That is when it stops looking harmless and starts looking like
     * the estate lost an object, so it is worth saying out loud beforehand.
     */
    public function engineMismatch(): bool
    {
        $reported = $this->reportedEngine();

        return $this->reachable && null !== $reported && $reported->value !== $this->runtime;
    }

    /**
     * The daemon named the way an operator has to think about it: where it is,
     * and what answered there.
     */
    public function describeDaemon(): string
    {
        $endpoint = $this->endpoint ?? 'an unconfigured endpoint';

        return null === $this->version
            ? $endpoint
            : sprintf('%s (%s)', $endpoint, $this->version);
    }
}
