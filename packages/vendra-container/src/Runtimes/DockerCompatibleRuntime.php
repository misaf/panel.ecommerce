<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Runtimes;

use Misaf\VendraContainer\Contracts\ContainerRuntime;
use Misaf\VendraContainer\Exceptions\ContainerNotFoundException;
use Misaf\VendraContainer\Exceptions\ContainerRuntimeException;
use Misaf\VendraContainer\Exceptions\RuntimeUnreachableException;
use Misaf\VendraContainer\Http\EngineApiClient;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\ContainerInfo;
use Misaf\VendraContainer\ValueObjects\ContainerLogs;
use Misaf\VendraContainer\ValueObjects\EnvironmentVariable;
use Misaf\VendraContainer\ValueObjects\ImageInfo;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\NetworkDefinition;
use Misaf\VendraContainer\ValueObjects\NetworkInfo;
use Misaf\VendraContainer\ValueObjects\RuntimeStatus;
use Misaf\VendraContainer\ValueObjects\VolumeMount;
use stdClass;

/**
 * Every runtime that speaks the Docker Engine API, which is both of them.
 *
 * Docker serves this API and Podman serves a compatibility socket for it, so the
 * whole client lives here once. A subclass exists to name the runtime and to
 * carry the defaults that genuinely differ — not to re-implement the protocol,
 * which is why this class is where all of it is.
 */
abstract class DockerCompatibleRuntime implements ContainerRuntime
{
    private const int NANOSECOND = 1_000_000_000;

    public function __construct(
        protected readonly EngineApiClient $engine,
        protected readonly int $pullTimeout = 600,
    ) {}

    /**
     * Confirm the endpoint answers, without throwing.
     *
     * The versioned path is tried first because every other call uses it: a
     * daemon that rejects the negotiated version should be reported here rather
     * than midway through a deployment.
     */
    public function ping(): RuntimeStatus
    {
        $response = $this->engine->get('/_ping');

        if ($response->successful()) {
            return RuntimeStatus::reachable(
                $this->runtimeName(),
                $this->engine->apiVersion(),
                $response->header('Server') ?: null,
                $this->engine->endpoint(),
            );
        }

        $unversioned = $this->engine->getUnversioned('/_ping');

        if ($unversioned->successful()) {
            return RuntimeStatus::unreachable(
                $this->runtimeName(),
                $this->engine->apiVersion(),
                RuntimeUnreachableException::apiVersion(
                    $this->engine->endpoint(),
                    $this->engine->apiVersion(),
                    $this->engine->reason($response),
                )->getMessage(),
                $this->engine->endpoint(),
            );
        }

        return RuntimeStatus::unreachable(
            $this->runtimeName(),
            $this->engine->apiVersion(),
            RuntimeUnreachableException::endpoint(
                $this->engine->endpoint(),
                $this->engine->reason($response),
            )->getMessage(),
            $this->engine->endpoint(),
        );
    }

    /**
     * Pull an image, streaming the progress feed to completion.
     *
     * The Engine reports a mid-stream failure inside a 200 response rather than
     * as a status code, so the body is inspected for an error frame instead of
     * the status alone being trusted.
     */
    public function pull(ImageReference $image): ImageInfo
    {
        $response = $this->engine->post(
            '/images/create',
            array_filter([
                'fromImage' => $image->repository,
                'tag'       => $image->isPinned() ? '' : $image->tag,
            ], static fn(string $value): bool => '' !== $value),
            timeout: $this->pullTimeout,
        );

        if ($response->failed()) {
            throw ContainerRuntimeException::pull($image, $this->engine->reason($response));
        }

        foreach (preg_split('/\r?\n/', $response->body()) ?: [] as $line) {
            if ('' === mb_trim($line)) {
                continue;
            }

            $frame = json_decode($line, true);

            if (is_array($frame) && isset($frame['error'])) {
                throw ContainerRuntimeException::pull(
                    $image,
                    is_string($frame['error']) ? $frame['error'] : 'unknown error',
                );
            }
        }

        return $this->inspectImage($image) ?? new ImageInfo($image, digest: $image->digest);
    }

    public function inspectImage(ImageReference $image): ?ImageInfo
    {
        $payload = $this->engine->decode(
            $this->engine->get('/images/' . rawurlencode($image->value) . '/json'),
        );

        return null === $payload ? null : ImageInfo::fromEnginePayload($image, $payload);
    }

    public function create(ContainerDefinition $definition): ContainerInfo
    {
        $response = $this->engine->post(
            '/containers/create',
            ['name' => $definition->name],
            $this->toEnginePayload($definition),
        );

        if ($response->failed()) {
            throw ContainerRuntimeException::create($definition->id(), $this->engine->reason($response));
        }

        $id = $response->json('Id');

        if ( ! is_string($id) || '' === $id) {
            throw ContainerRuntimeException::create($definition->id(), 'the runtime returned no container id');
        }

        return $this->inspect(new ContainerId($id));
    }

    public function start(ContainerId $container): void
    {
        $response = $this->engine->post('/containers/' . rawurlencode($container->value) . '/start');

        // 304 means the container is already running, which satisfies the caller.
        if ($response->failed() && 304 !== $response->status()) {
            throw ContainerRuntimeException::start($container, $this->engine->reason($response));
        }
    }

    public function stop(ContainerId $container): void
    {
        $response = $this->engine->post('/containers/' . rawurlencode($container->value) . '/stop');

        // 304 means it is already stopped, which is what the caller wanted.
        if ($response->failed() && 304 !== $response->status()) {
            throw ContainerRuntimeException::stop($container, $this->engine->reason($response));
        }
    }

    public function restart(ContainerId $container): void
    {
        $response = $this->engine->post('/containers/' . rawurlencode($container->value) . '/restart');

        if ($response->failed()) {
            throw ContainerRuntimeException::restart($container, $this->engine->reason($response));
        }
    }

    /**
     * Remove a container if it exists. A 404 is success: the caller wants it gone.
     */
    public function remove(ContainerId $container): void
    {
        $response = $this->engine->delete('/containers/' . rawurlencode($container->value), [
            'force' => 'true',
            'v'     => 'true',
        ]);

        if ($response->failed() && 404 !== $response->status()) {
            throw ContainerRuntimeException::remove($container, $this->engine->reason($response));
        }
    }

    public function inspect(ContainerId $container): ContainerInfo
    {
        return $this->find($container) ?? throw ContainerNotFoundException::for($container);
    }

    public function find(ContainerId $container): ?ContainerInfo
    {
        $payload = $this->engine->decode(
            $this->engine->get('/containers/' . rawurlencode($container->value) . '/json'),
        );

        return null === $payload ? null : ContainerInfo::fromEnginePayload($payload);
    }

    public function logs(ContainerId $container, int $lines = 200): ContainerLogs
    {
        $response = $this->engine->get('/containers/' . rawurlencode($container->value) . '/logs', [
            'stdout' => 'true',
            'stderr' => 'true',
            'tail'   => (string) max($lines, 1),
        ]);

        if ($response->failed()) {
            throw 404 === $response->status()
                ? ContainerNotFoundException::for($container)
                : ContainerRuntimeException::logs($container, $this->engine->reason($response));
        }

        return new ContainerLogs($container, $this->stripStreamHeaders($response->body()));
    }

    public function findNetwork(string $name): ?NetworkInfo
    {
        $response = $this->engine->get('/networks/' . rawurlencode($name));

        if ($response->failed()) {
            return null;
        }

        return NetworkInfo::fromEnginePayload($name, $this->engine->decode($response) ?? []);
    }

    public function createNetwork(NetworkDefinition $definition): NetworkInfo
    {
        $response = $this->engine->post('/networks/create', payload: array_filter([
            'Name'       => $definition->name,
            'Driver'     => $definition->driver,
            'Attachable' => $definition->attachable,
            'Internal'   => $definition->internal,
            'Labels'     => $definition->labels,
        ], static fn(mixed $value): bool => [] !== $value));

        if ($response->failed()) {
            throw ContainerRuntimeException::network($definition->name, $this->engine->reason($response));
        }

        return $this->findNetwork($definition->name)
            ?? new NetworkInfo($definition->name, driver: $definition->driver);
    }

    /**
     * The runtime's own name, for operator-facing messages only.
     */
    abstract protected function runtimeName(): string;

    /**
     * Translate a definition into the Engine's create payload.
     *
     * This method is the whole reason the layers above own no Docker knowledge:
     * everything Docker-shaped about a container — nanosecond intervals, the
     * "KEY=VALUE" environment, `ExposedPorts` keyed by "3000/tcp", the empty
     * object that means "attach with defaults" — is applied here and nowhere else.
     *
     * @return array<string, mixed>
     */
    protected function toEnginePayload(ContainerDefinition $definition): array
    {
        $payload = [
            'Image'  => $definition->image->value,
            'Env'    => array_map(static fn(EnvironmentVariable $variable): string => (string) $variable, $definition->environment),
            'Labels' => $definition->labels,
        ];

        if (null !== $definition->command) {
            $payload['Cmd'] = $definition->command;
        }

        $exposedPorts = [];

        foreach ($definition->ports as $port) {
            $exposedPorts[$port->key()] = new stdClass();
        }

        if ([] !== $exposedPorts) {
            $payload['ExposedPorts'] = $exposedPorts;
        }

        if (null !== $definition->healthCheck) {
            $payload['Healthcheck'] = [
                'Test'        => $definition->healthCheck->test,
                'Interval'    => $definition->healthCheck->intervalSeconds * self::NANOSECOND,
                'Timeout'     => $definition->healthCheck->timeoutSeconds * self::NANOSECOND,
                'Retries'     => $definition->healthCheck->retries,
                'StartPeriod' => $definition->healthCheck->startPeriodSeconds * self::NANOSECOND,
            ];
        }

        $payload['HostConfig'] = array_filter([
            'RestartPolicy' => null === $definition->restartPolicy ? [] : array_filter([
                'Name'              => $definition->restartPolicy->name,
                'MaximumRetryCount' => $definition->restartPolicy->maximumRetryCount,
            ], static fn(mixed $value): bool => 0 !== $value),
            'SecurityOpt'  => $definition->securityOptions,
            'Binds'        => array_map(static fn(VolumeMount $volume): string => $volume->toBind(), $definition->volumes),
            'PortBindings' => $this->portBindings($definition),
            'LogConfig'    => $this->logConfig($definition),
            'NetworkMode'  => $definition->primaryNetwork() ?? '',
            ...$this->resourceLimits($definition),
        ], static fn(mixed $value): bool => [] !== $value && '' !== $value);

        $endpoints = [];

        foreach ($definition->networks as $network) {
            $endpoints[$network] = new stdClass();
        }

        if ([] !== $endpoints) {
            $payload['NetworkingConfig'] = ['EndpointsConfig' => $endpoints];
        }

        return $payload;
    }

    /**
     * The Engine's own names for the caps a definition asks for.
     *
     * Only the limits actually set are emitted: a zero in any of these keys is
     * how the Engine spells "unlimited", so sending one for an unset limit
     * would work by accident rather than by intent.
     *
     * @return array<string, int>
     */
    private function resourceLimits(ContainerDefinition $definition): array
    {
        if ( ! $definition->isCapped()) {
            return [];
        }

        $limits = $definition->resources;

        return array_filter([
            'NanoCpus'          => $limits?->nanoCpus(),
            'Memory'            => $limits?->memoryBytes(),
            'MemoryReservation' => $limits?->memoryReservationBytes(),
        ], static fn(?int $value): bool => null !== $value);
    }

    /**
     * @return array<string, list<array<string, string>>>
     */
    private function portBindings(ContainerDefinition $definition): array
    {
        $bindings = [];

        foreach ($definition->ports as $port) {
            if ( ! $port->isPublished()) {
                continue;
            }

            $bindings[$port->key()] = [array_filter([
                'HostIp'   => $port->hostIp ?? '',
                'HostPort' => (string) $port->hostPort,
            ], static fn(string $value): bool => '' !== $value)];
        }

        return $bindings;
    }

    /**
     * @return array<string, mixed>
     */
    private function logConfig(ContainerDefinition $definition): array
    {
        if (null === $definition->logConfiguration || ! $definition->logConfiguration->isConfigured()) {
            return [];
        }

        return array_filter([
            'Type'   => $definition->logConfiguration->driver,
            'Config' => $definition->logConfiguration->options,
        ], static fn(mixed $value): bool => [] !== $value);
    }

    /**
     * Strip the 8-byte multiplexing header the Engine prefixes each log frame with.
     *
     * Present only when the container has no TTY, which is the normal case for a
     * service, and the bytes are otherwise rendered as control characters in the
     * middle of every line.
     */
    private function stripStreamHeaders(string $body): string
    {
        $output = '';
        $offset = 0;
        $length = mb_strlen($body, '8bit');

        while ($offset + 8 <= $length) {
            $header = mb_substr($body, $offset, 8, '8bit');
            $stream = ord($header[0]);

            if ($stream > 2 || "\0" !== $header[1] || "\0" !== $header[2] || "\0" !== $header[3]) {
                return $body;
            }

            /** @var array{1: int} $unpacked */
            $unpacked = unpack('N', mb_substr($header, 4, 4, '8bit'));
            $frame = $unpacked[1];
            $output .= mb_substr($body, $offset + 8, $frame, '8bit');
            $offset += 8 + $frame;
        }

        return '' === $output ? $body : $output;
    }
}
