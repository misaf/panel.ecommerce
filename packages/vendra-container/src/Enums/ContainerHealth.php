<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Enums;

/**
 * The health a runtime reports for a container.
 *
 * `None` covers two different situations and only one of them is benign: an
 * image with no health check, or a runtime that is not executing the one it was
 * given — Podman runs health checks through transient systemd timers, so without
 * systemd the state stays empty. Callers that asked for a check should say so in
 * their logs rather than read `None` as proof of health.
 */
enum ContainerHealth: string
{
    case None = 'none';
    case Starting = 'starting';
    case Healthy = 'healthy';
    case Unhealthy = 'unhealthy';

    public static function fromRuntime(?string $status): self
    {
        if (null === $status || '' === mb_trim($status)) {
            return self::None;
        }

        return self::tryFrom(mb_strtolower(mb_trim($status))) ?? self::None;
    }

    /**
     * Whether the runtime is reporting a health state at all.
     */
    public function isReported(): bool
    {
        return self::None !== $this;
    }
}
