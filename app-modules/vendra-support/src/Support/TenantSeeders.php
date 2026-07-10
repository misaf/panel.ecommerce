<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

/**
 * Registry of tenant seed commands, populated by each seedable module from its
 * own service provider. The provisioning flow runs them in ascending priority
 * order, so no single module has to know the full list of seeders.
 */
final class TenantSeeders
{
    /**
     * @var array<string, int> seed command signature => priority
     */
    private array $commands = [];

    public function register(string $command, int $priority = 100): void
    {
        $this->commands[$command] = $priority;
    }

    /**
     * Seed command signatures ordered by ascending priority. PHP's asort is
     * stable, so commands registered with equal priority keep insertion order.
     *
     * @return list<string>
     */
    public function ordered(): array
    {
        $commands = $this->commands;

        asort($commands);

        return array_keys($commands);
    }
}
