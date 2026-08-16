<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Support;

use Illuminate\Support\Facades\Config;
use Misaf\VendraContainer\Runtimes\DockerRuntime;
use Misaf\VendraContainer\Runtimes\PodmanRuntime;

/**
 * The `vendra-container` config block as an immutable, typed value.
 *
 * Read once, here, so no consumer re-implements the same string/int coercion and
 * no caller reaches into `Config` for a runtime setting. `isConfigured()` is the
 * question callers actually ask — "can anything be deployed at all?" — and it is
 * deliberately answered without touching the network, so a caller can record
 * intent and reconcile later instead of blocking on a daemon that is not up.
 */
final class ContainerRuntimeConfiguration
{
    public function __construct(
        public readonly string $runtime,
        public readonly string $endpoint,
        public readonly string $apiVersion,
        public readonly int $timeout,
        public readonly int $pullTimeout,
    ) {}

    public static function fromConfig(): self
    {
        $runtime = self::normalisedRuntime(Config::string('vendra-container.runtime', DockerRuntime::NAME));

        return new self(
            runtime: $runtime,
            endpoint: mb_trim(Config::string('vendra-container.endpoint', '')),
            apiVersion: self::apiVersion($runtime),
            timeout: max(Config::integer('vendra-container.timeout', 60), 1),
            pullTimeout: max(Config::integer('vendra-container.pull_timeout', 600), 1),
        );
    }

    /**
     * Whether a runtime endpoint has been configured at all.
     */
    public function isConfigured(): bool
    {
        return '' !== $this->endpoint;
    }

    /**
     * Operator-facing reason nothing can be deployed.
     */
    public function misconfigurationMessage(): string
    {
        return 'Configure CONTAINER_ENDPOINT first.';
    }

    public function usesPodman(): bool
    {
        return PodmanRuntime::NAME === $this->runtime;
    }

    /**
     * An unknown runtime name falls back to Docker rather than throwing.
     *
     * Both runtimes serve the same API, so the Docker adapter is the one that
     * works against anything Engine-compatible; refusing to boot over a typo in
     * an env var would be the worse failure.
     */
    private static function normalisedRuntime(string $runtime): string
    {
        $runtime = mb_strtolower(mb_trim($runtime));

        return PodmanRuntime::NAME === $runtime ? PodmanRuntime::NAME : DockerRuntime::NAME;
    }

    private static function apiVersion(string $runtime): string
    {
        $configured = mb_trim(Config::string('vendra-container.api_version', ''));

        if ('' !== $configured) {
            return $configured;
        }

        return PodmanRuntime::NAME === $runtime
            ? PodmanRuntime::DEFAULT_API_VERSION
            : DockerRuntime::DEFAULT_API_VERSION;
    }
}
