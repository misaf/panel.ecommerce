<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Enums;

/**
 * The lifecycle state a runtime reports for a container.
 */
enum ContainerState: string
{
    case Created = 'created';
    case Restarting = 'restarting';
    case Running = 'running';
    case Removing = 'removing';
    case Paused = 'paused';
    case Exited = 'exited';
    case Dead = 'dead';
    case Unknown = 'unknown';

    /**
     * Map a runtime-reported status, defaulting to Unknown rather than throwing.
     *
     * An unrecognised status is a runtime the caller has not seen before, not a
     * programming error, and treating it as terminal would stop a deployment
     * that may be fine.
     */
    public static function fromRuntime(?string $status): self
    {
        return self::tryFrom(mb_strtolower(mb_trim((string) $status))) ?? self::Unknown;
    }

    /**
     * Whether the container has stopped for good.
     */
    public function hasStopped(): bool
    {
        return match ($this) {
            self::Exited, self::Dead => true,
            default                  => false,
        };
    }
}
