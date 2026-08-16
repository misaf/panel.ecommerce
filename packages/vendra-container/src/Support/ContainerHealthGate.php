<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Support;

use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Enums\ContainerHealth;
use Misaf\VendraContainer\Exceptions\ContainerRuntimeException;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Psr\Log\LoggerInterface;

/**
 * Waits for a freshly started container to prove itself.
 *
 * Generic on purpose: "poll until healthy, give up at the deadline, fail fast if
 * it exits" is runtime behaviour, not application policy, and a caller doing it
 * itself would be back to reading `State.Health.Status`.
 *
 * A container reporting no health state at all is treated as ready once it runs,
 * because there is nothing better to wait for. That happens for two reasons and
 * only one is benign: an image with no health check, or a runtime not executing
 * the one it was given — Podman runs health checks through transient systemd
 * timers, so without systemd the state stays empty and the gate silently
 * degrades to "started". It is logged rather than failed, since a running
 * container is still a deployed container.
 */
final class ContainerHealthGate
{
    private const int POLL_MICROSECONDS = 2_000_000;

    public function __construct(private readonly LoggerInterface $logger) {}

    /**
     * Whether the container reached a serving state within the budget.
     *
     * @param bool $expectsHealthCheck whether a health check was asked for, which
     *                                 decides only whether a missing health state
     *                                 is worth a warning
     *
     * @throws ContainerRuntimeException when the container exits while being waited on
     */
    public function await(
        ContainerRuntime $runtime,
        ContainerId $container,
        int $timeoutSeconds,
        bool $expectsHealthCheck = true,
    ): bool {
        $deadline = microtime(true) + max($timeoutSeconds, 1);

        do {
            $info = $runtime->inspect($container);

            if ($info->state->hasStopped()) {
                throw new ContainerRuntimeException(sprintf(
                    'The container [%s] exited while starting with code %s.',
                    $container,
                    null === $info->exitCode ? 'unknown' : (string) $info->exitCode,
                ));
            }

            if (ContainerHealth::Healthy === $info->health) {
                return true;
            }

            if ( ! $info->health->isReported() && $info->isRunning()) {
                if ($expectsHealthCheck) {
                    $this->logger->warning(
                        'Container reports no health state; treating it as ready once running.',
                        ['container' => $container->value],
                    );
                }

                return true;
            }

            if (microtime(true) >= $deadline) {
                return false;
            }

            usleep(self::POLL_MICROSECONDS);
        } while (true);
    }

    /**
     * The same wait, expressed against the definition that was deployed.
     */
    public function awaitDefinition(
        ContainerRuntime $runtime,
        ContainerDefinition $definition,
        int $timeoutSeconds,
    ): bool {
        return $this->await(
            $runtime,
            $definition->id(),
            $timeoutSeconds,
            expectsHealthCheck: $definition->expectsHealthChecks(),
        );
    }
}
