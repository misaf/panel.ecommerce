<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Testing;

use Closure;
use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Enums\ContainerHealth;
use Misaf\VendraContainer\Enums\ContainerState;
use Misaf\VendraContainer\Exceptions\ContainerNotFoundException;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\ContainerInfo;
use Misaf\VendraContainer\ValueObjects\ContainerLogs;
use Misaf\VendraContainer\ValueObjects\ImageInfo;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\NetworkDefinition;
use Misaf\VendraContainer\ValueObjects\NetworkInfo;
use Misaf\VendraContainer\ValueObjects\RuntimeStatus;

/**
 * An in-memory runtime for tests of the layers above.
 *
 * Domain tests should assert what was asked of the runtime, not that an HTTP
 * client was called in a particular order — and none of them should need a real
 * daemon. Shipped from the package rather than rebuilt per consumer so a change
 * to the contract breaks one fake instead of several.
 */
final class FakeContainerRuntime implements ContainerRuntime
{
    public const string NAME = 'fake';

    public const string ENDPOINT = 'memory://fake-runtime';

    /** @var array<string, ContainerInfo> */
    private array $containers = [];

    /** @var array<string, ContainerDefinition> */
    private array $definitions = [];

    /** @var array<string, NetworkInfo> */
    private array $networks = [];

    /** @var array<string, string> */
    private array $logs = [];

    /** @var list<string> */
    public array $calls = [];

    /** @var list<ImageReference> */
    public array $pulled = [];

    private bool $reachable = true;

    private ContainerHealth $healthOnStart = ContainerHealth::Healthy;

    /** @var array<string, Closure(ContainerId): void> */
    private array $failures = [];

    public function withNetwork(string $name): self
    {
        $this->networks[$name] = new NetworkInfo($name, driver: 'bridge');

        return $this;
    }

    /**
     * Place an already-running container, for callers that observe rather than deploy.
     *
     * Going through `create()` and `start()` would record calls the test never
     * made and demand a full definition it does not care about, so the state is
     * seeded directly.
     */
    public function withRunningContainer(string $name, ContainerHealth $health = ContainerHealth::Healthy): self
    {
        $this->containers[$name] = new ContainerInfo(
            id: new ContainerId($name),
            name: $name,
            state: ContainerState::Running,
            health: $health,
        );

        return $this;
    }

    public function unreachable(): self
    {
        $this->reachable = false;

        return $this;
    }

    /**
     * Make started containers report this health, so the caller's health gate
     * can be driven without waiting on a real probe.
     */
    public function reportingHealth(ContainerHealth $health): self
    {
        $this->healthOnStart = $health;

        return $this;
    }

    /**
     * Make a container exit as soon as it is started.
     */
    public function failingOnStart(string $name, int $exitCode = 1): self
    {
        $this->failures[$name] = function (ContainerId $container) use ($exitCode): void {
            $existing = $this->containers[$container->value];

            $this->containers[$container->value] = new ContainerInfo(
                id: $existing->id,
                name: $existing->name,
                state: ContainerState::Exited,
                health: ContainerHealth::None,
                exitCode: $exitCode,
                image: $existing->image,
                labels: $existing->labels,
            );
        };

        return $this;
    }

    public function withLogs(string $name, string $output): self
    {
        $this->logs[$name] = $output;

        return $this;
    }

    public function ping(): RuntimeStatus
    {
        $this->calls[] = 'ping';

        return $this->reachable
            ? RuntimeStatus::reachable(self::NAME, 'v1.43', endpoint: self::ENDPOINT)
            : RuntimeStatus::unreachable(self::NAME, 'v1.43', 'The fake runtime is configured as unreachable.', self::ENDPOINT);
    }

    public function pull(ImageReference $image): ImageInfo
    {
        $this->calls[] = 'pull:' . $image->value;
        $this->pulled[] = $image;

        return new ImageInfo($image, digest: $image->digest ?? 'sha256:' . mb_substr(hash('sha256', $image->value), 0, 64));
    }

    public function inspectImage(ImageReference $image): ?ImageInfo
    {
        return new ImageInfo($image, digest: $image->digest ?? 'sha256:' . mb_substr(hash('sha256', $image->value), 0, 64));
    }

    public function create(ContainerDefinition $definition): ContainerInfo
    {
        $this->calls[] = 'create:' . $definition->name;
        $this->definitions[$definition->name] = $definition;

        $info = new ContainerInfo(
            id: $definition->id(),
            name: $definition->name,
            state: ContainerState::Created,
            image: $definition->image->value,
            labels: $definition->labels,
        );

        $this->containers[$definition->name] = $info;

        return $info;
    }

    public function start(ContainerId $container): void
    {
        $this->calls[] = 'start:' . $container->value;
        $this->assertExists($container);

        $existing = $this->containers[$container->value];

        $this->containers[$container->value] = new ContainerInfo(
            id: $existing->id,
            name: $existing->name,
            state: ContainerState::Running,
            health: $this->healthOnStart,
            image: $existing->image,
            labels: $existing->labels,
        );

        ($this->failures[$container->value] ?? null)?->__invoke($container);
    }

    public function stop(ContainerId $container): void
    {
        $this->calls[] = 'stop:' . $container->value;
        $this->assertExists($container);

        $existing = $this->containers[$container->value];

        $this->containers[$container->value] = new ContainerInfo(
            id: $existing->id,
            name: $existing->name,
            state: ContainerState::Exited,
            exitCode: 0,
            image: $existing->image,
            labels: $existing->labels,
        );
    }

    public function restart(ContainerId $container): void
    {
        $this->calls[] = 'restart:' . $container->value;
        $this->assertExists($container);
        $this->start($container);
        array_pop($this->calls);
    }

    public function remove(ContainerId $container): void
    {
        $this->calls[] = 'remove:' . $container->value;

        unset($this->containers[$container->value], $this->definitions[$container->value]);
    }

    public function inspect(ContainerId $container): ContainerInfo
    {
        return $this->find($container) ?? throw ContainerNotFoundException::for($container);
    }

    public function find(ContainerId $container): ?ContainerInfo
    {
        return $this->containers[$container->value] ?? null;
    }

    public function logs(ContainerId $container, int $lines = 200): ContainerLogs
    {
        $this->calls[] = 'logs:' . $container->value;
        $this->assertExists($container);

        return new ContainerLogs($container, $this->logs[$container->value] ?? '');
    }

    public function findNetwork(string $name): ?NetworkInfo
    {
        return $this->networks[$name] ?? null;
    }

    public function createNetwork(NetworkDefinition $definition): NetworkInfo
    {
        $this->calls[] = 'createNetwork:' . $definition->name;

        return $this->networks[$definition->name] = new NetworkInfo($definition->name, driver: $definition->driver);
    }

    /**
     * The definition a container was created from, for asserting *what* was asked for.
     */
    public function definitionFor(string $name): ?ContainerDefinition
    {
        return $this->definitions[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->containers);
    }

    /**
     * @return list<string>
     */
    public function names(): array
    {
        return array_keys($this->containers);
    }

    private function assertExists(ContainerId $container): void
    {
        if ( ! array_key_exists($container->value, $this->containers)) {
            throw ContainerNotFoundException::for($container);
        }
    }
}
