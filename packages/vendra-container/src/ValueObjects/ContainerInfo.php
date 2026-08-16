<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Illuminate\Support\Arr;
use Misaf\VendraContainer\Enums\ContainerHealth;
use Misaf\VendraContainer\Enums\ContainerState;

/**
 * What a runtime reports about one container.
 *
 * The inspect body of an Engine-compatible runtime is large, nested, and only
 * partly the same across implementations. Reducing it here is what lets callers
 * ask `->isRunning()` instead of reaching into `State.Health.Status` and coping
 * with the two runtimes' differences themselves.
 */
final class ContainerInfo
{
    /**
     * @param array<string, string> $labels
     */
    public function __construct(
        public readonly ContainerId $id,
        public readonly string $name,
        public readonly ContainerState $state,
        public readonly ContainerHealth $health = ContainerHealth::None,
        public readonly ?int $exitCode = null,
        public readonly ?string $image = null,
        public readonly ?string $imageDigest = null,
        public readonly array $labels = [],
    ) {}

    /**
     * Build from an Engine container-inspect body.
     *
     * @param array<array-key, mixed> $payload
     */
    public static function fromEnginePayload(array $payload): self
    {
        $id = self::string($payload, 'Id');
        $name = mb_ltrim(self::string($payload, 'Name') ?? '', '/');
        $exitCode = Arr::get($payload, 'State.ExitCode');

        return new self(
            id: new ContainerId($id ?? $name),
            name: $name,
            state: ContainerState::fromRuntime(self::string($payload, 'State.Status')),
            health: ContainerHealth::fromRuntime(self::string($payload, 'State.Health.Status')),
            exitCode: is_numeric($exitCode) ? (int) $exitCode : null,
            image: self::string($payload, 'Config.Image'),
            imageDigest: self::string($payload, 'Image'),
            labels: self::labels($payload),
        );
    }

    public function isRunning(): bool
    {
        return ContainerState::Running === $this->state;
    }

    /**
     * Whether the container is up and nothing contradicts its health.
     *
     * A runtime reporting no health state is accepted once the container runs,
     * because there is nothing better to wait for; the caller that asked for a
     * check is the one that should notice it never arrived.
     */
    public function isServing(): bool
    {
        return match ($this->health) {
            ContainerHealth::Healthy   => true,
            ContainerHealth::None      => $this->isRunning(),
            default                    => false,
        };
    }

    public function hasLabel(string $name, ?string $value = null): bool
    {
        if ( ! array_key_exists($name, $this->labels)) {
            return false;
        }

        return null === $value || $this->labels[$name] === $value;
    }

    /**
     * @param  array<array-key, mixed> $payload
     * @return array<string, string>
     */
    private static function labels(array $payload): array
    {
        $labels = [];
        $configured = Arr::get($payload, 'Config.Labels');

        foreach (is_array($configured) ? $configured : [] as $name => $value) {
            if (is_string($name) && is_string($value)) {
                $labels[$name] = $value;
            }
        }

        return $labels;
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    private static function string(array $payload, string $key): ?string
    {
        $value = Arr::get($payload, $key);

        return is_string($value) && '' !== $value ? $value : null;
    }
}
