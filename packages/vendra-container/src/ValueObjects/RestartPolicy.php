<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

/**
 * What the runtime does when the container exits.
 */
final class RestartPolicy
{
    private function __construct(
        public readonly string $name,
        public readonly int $maximumRetryCount = 0,
    ) {}

    public static function never(): self
    {
        return new self('no');
    }

    public static function unlessStopped(): self
    {
        return new self('unless-stopped');
    }

    public static function always(): self
    {
        return new self('always');
    }

    public static function onFailure(int $maximumRetryCount = 3): self
    {
        return new self('on-failure', $maximumRetryCount);
    }
}
