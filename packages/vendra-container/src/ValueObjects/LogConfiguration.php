<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

/**
 * The log driver a container writes through, and its options.
 *
 * Named rather than assumed: "json-file" and its max-size/max-file options are a
 * Docker default that Podman does not share — it logs through "k8s-file" or
 * journald and rejects options meant for a driver it is not using. An empty
 * driver leaves logging to whatever the runtime is configured to do, which is the
 * safe setting on a runtime whose driver has not been confirmed.
 */
final class LogConfiguration
{
    /**
     * @param array<string, string> $options
     */
    public function __construct(
        public readonly string $driver,
        public readonly array $options = [],
    ) {}

    /**
     * Whether anything should be sent to the runtime at all.
     */
    public function isConfigured(): bool
    {
        return '' !== mb_trim($this->driver);
    }
}
