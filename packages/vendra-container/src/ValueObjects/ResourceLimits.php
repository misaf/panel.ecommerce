<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * How much of the host one container may take.
 *
 * Expressed the way an operator thinks about it — "half a core, 512 MB" — not
 * the way the Engine wants it. Nano-CPUs and byte counts are the adapter's
 * problem; anything above this package should never have to know that a CPU
 * limit is a billion times a fraction.
 *
 * Every limit is optional, and an unset one means "no cap": on a fleet of
 * storefront containers, capping memory while leaving CPU to the scheduler is a
 * legitimate configuration, not a half-filled value.
 */
final class ResourceLimits
{
    /** Nano-CPUs in one CPU, as the Engine counts them. */
    private const int NANO_CPUS = 1_000_000_000;

    private const int BYTES_PER_MEGABYTE = 1024 * 1024;

    /**
     * @param float|null $cpus            CPU cores, fractional (0.5 = half a core)
     * @param int|null   $memoryMegabytes hard memory cap
     * @param int|null   $memoryReservationMegabytes soft cap the runtime reclaims down to
     * @param int|null   $pidsLimit       processes and threads the container may hold open at once
     */
    public function __construct(
        public readonly ?float $cpus = null,
        public readonly ?int $memoryMegabytes = null,
        public readonly ?int $memoryReservationMegabytes = null,
        public readonly ?int $pidsLimit = null,
    ) {
        if (null !== $cpus && $cpus <= 0) {
            throw new InvalidArgumentException('A CPU limit must be greater than zero.');
        }

        if (null !== $memoryMegabytes && $memoryMegabytes <= 0) {
            throw new InvalidArgumentException('A memory limit must be greater than zero.');
        }

        if (null !== $memoryReservationMegabytes && $memoryReservationMegabytes <= 0) {
            throw new InvalidArgumentException('A memory reservation must be greater than zero.');
        }

        if (null !== $pidsLimit && $pidsLimit <= 0) {
            throw new InvalidArgumentException('A PID limit must be greater than zero.');
        }

        if (null !== $memoryMegabytes && null !== $memoryReservationMegabytes && $memoryReservationMegabytes > $memoryMegabytes) {
            throw new InvalidArgumentException('A memory reservation may not exceed the memory limit.');
        }
    }

    /**
     * Whether anything is actually capped.
     *
     * A definition carrying an all-null instance is the same as carrying none,
     * so the adapter can leave the Engine's resource keys out entirely.
     */
    public function isConfigured(): bool
    {
        return null !== $this->cpus
            || null !== $this->memoryMegabytes
            || null !== $this->memoryReservationMegabytes
            || null !== $this->pidsLimit;
    }

    public function nanoCpus(): ?int
    {
        return null === $this->cpus ? null : (int) round($this->cpus * self::NANO_CPUS);
    }

    public function memoryBytes(): ?int
    {
        return null === $this->memoryMegabytes ? null : $this->memoryMegabytes * self::BYTES_PER_MEGABYTE;
    }

    public function memoryReservationBytes(): ?int
    {
        return null === $this->memoryReservationMegabytes
            ? null
            : $this->memoryReservationMegabytes * self::BYTES_PER_MEGABYTE;
    }
}
