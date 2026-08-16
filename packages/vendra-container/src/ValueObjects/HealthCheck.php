<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * The probe a runtime runs against a container to decide whether it is healthy.
 *
 * Intervals are given in seconds because that is the unit callers reason in; the
 * Engine's nanoseconds are an encoding detail of the adapter.
 */
final class HealthCheck
{
    /**
     * @param list<string> $test the Engine test vector, e.g. ['CMD', 'node', '-e', '…']
     */
    public function __construct(
        public readonly array $test,
        public readonly int $intervalSeconds = 10,
        public readonly int $timeoutSeconds = 3,
        public readonly int $retries = 12,
        public readonly int $startPeriodSeconds = 15,
    ) {
        if ([] === $test) {
            throw new InvalidArgumentException('A health check needs a test command.');
        }
    }
}
