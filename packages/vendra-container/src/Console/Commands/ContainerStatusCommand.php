<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Support\ContainerRuntimeConfiguration;

/**
 * Answer "which daemon am I actually talking to?" in one command.
 *
 * Every setting here is readable from config and every fact is readable from a
 * ping, but an operator diagnosing a deployment has to correlate the two, and
 * the failure worth catching lives exactly in the gap between them: an endpoint
 * that has been moved to another daemon still answers, still passes a ping, and
 * only fails later as an object that has apparently gone missing. So the
 * configured intent and the daemon's own account of itself are printed side by
 * side, and disagreement is called out rather than left to be noticed.
 */
final class ContainerStatusCommand extends Command
{
    protected $signature = 'container:status {--network=* : Also report whether these networks exist on that daemon}';

    protected $description = 'Report the configured container runtime and what actually answers there';

    public function handle(ContainerRuntimeConfiguration $configuration, ContainerRuntime $runtime): int
    {
        if ( ! $configuration->isConfigured()) {
            $this->components->error($configuration->misconfigurationMessage());

            return self::FAILURE;
        }

        $status = $runtime->ping();

        $this->components->twoColumnDetail('Configured runtime', $configuration->runtime);
        $this->components->twoColumnDetail('Endpoint', $configuration->endpoint);
        $this->components->twoColumnDetail('API version', $configuration->apiVersion);
        $this->components->twoColumnDetail('Reachable', $status->reachable ? '<fg=green>yes</>' : '<fg=red>no</>');
        $this->components->twoColumnDetail('Reported engine', $status->version ?? '—');

        if ( ! $status->reachable) {
            $this->newLine();
            $this->components->error($status->message ?? 'The container runtime is not reachable.');

            return self::FAILURE;
        }

        $this->newLine();

        $healthy = $this->reportEngineAgreement($status->engineMismatch(), $status->reportedEngine()?->value, $configuration->runtime);

        foreach ($this->networks() as $network) {
            $healthy = $this->reportNetwork($runtime, $network) && $healthy;
        }

        return $healthy ? self::SUCCESS : self::FAILURE;
    }

    /**
     * @return list<string>
     */
    private function networks(): array
    {
        /** @var list<string> $networks */
        $networks = (array) $this->option('network');

        return array_values(array_filter(array_map(mb_trim(...), $networks), static fn(string $network): bool => '' !== $network));
    }

    private function reportEngineAgreement(bool $mismatch, ?string $reported, string $configured): bool
    {
        if ( ! $mismatch) {
            return true;
        }

        $this->components->warn(sprintf(
            'This endpoint is serving %s, but CONTAINER_RUNTIME is set to %s. Both speak the same API, so nothing '
            . 'fails immediately — but networks, images, and containers are per-daemon, so anything placed through '
            . 'the other runtime will look as though it has gone missing.',
            $reported,
            $configured,
        ));

        return false;
    }

    private function reportNetwork(ContainerRuntime $runtime, string $network): bool
    {
        if (null !== $runtime->findNetwork($network)) {
            $this->components->info(sprintf('The network [%s] exists on this daemon.', $network));

            return true;
        }

        $this->components->error(sprintf(
            'The network [%s] does not exist on this daemon. Storefronts cannot be provisioned until it is created '
            . 'alongside the rest of the estate.',
            $network,
        ));

        return false;
    }
}
