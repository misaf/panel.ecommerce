<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Misaf\VendraContainer\Enums\ContainerEngine;
use Misaf\VendraContainer\Enums\ContainerState;
use Misaf\VendraContainer\Exceptions\ContainerNotFoundException;
use Misaf\VendraContainer\Exceptions\ContainerRuntimeException;
use Misaf\VendraContainer\Http\EngineApiClient;
use Misaf\VendraContainer\Runtimes\DockerRuntime;
use Misaf\VendraContainer\Runtimes\PodmanRuntime;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\EnvironmentVariable;
use Misaf\VendraContainer\ValueObjects\HealthCheck;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\LogConfiguration;
use Misaf\VendraContainer\ValueObjects\NetworkDefinition;
use Misaf\VendraContainer\ValueObjects\PortBinding;
use Misaf\VendraContainer\ValueObjects\ResourceLimits;
use Misaf\VendraContainer\ValueObjects\RestartPolicy;
use Misaf\VendraContainer\ValueObjects\VolumeMount;

function containerRuntimeEngine(string $endpoint = 'tcp://runtime.test:2375'): EngineApiClient
{
    return new EngineApiClient($endpoint, 'v1.43');
}

function containerRuntimeDefinition(?LogConfiguration $logConfiguration = null): ContainerDefinition
{
    return new ContainerDefinition(
        name: 'vendra-storefront-flowers',
        image: new ImageReference('ghcr.io/misaf/storefront:1.0.0'),
        environment: EnvironmentVariable::collection([
            'STORE_ID'    => '101',
            'DOMAIN'      => 'flowers-a.com',
        ]),
        labels: ['io.vendra.managed-by' => 'vendra'],
        ports: [new PortBinding(3000)],
        volumes: [VolumeMount::readOnly('/srv/certs', '/certs')],
        networks: ['traefik-public'],
        healthCheck: new HealthCheck(['CMD', 'node', '-e', 'ok']),
        restartPolicy: RestartPolicy::unlessStopped(),
        logConfiguration: $logConfiguration ?? new LogConfiguration('json-file', ['max-size' => '10m']),
        securityOptions: ['no-new-privileges:true'],
    );
}

/**
 * @return array<string, mixed>
 */
function containerInspectBody(string $status = 'running', ?string $health = 'healthy'): array
{
    return [
        'Id'    => 'container-id',
        'Name'  => '/vendra-storefront-flowers',
        'State' => array_filter([
            'Status'   => $status,
            'ExitCode' => 0,
            'Health'   => null === $health ? null : ['Status' => $health],
        ]),
        'Config' => ['Image' => 'ghcr.io/misaf/storefront:1.0.0', 'Labels' => []],
    ];
}

it('reports a reachable runtime', function (): void {
    Http::fake(['*/v1.43/_ping' => Http::response('OK')]);

    $status = (new DockerRuntime(containerRuntimeEngine()))->ping();

    expect($status->reachable)->toBeTrue()
        ->and($status->runtime)->toBe('docker')
        ->and($status->apiVersion)->toBe('v1.43');
});

it('separates a rejected API version from a dead endpoint', function (): void {
    Http::fake([
        '*/v1.43/_ping' => Http::response('bad version', 400),
        '*/_ping'       => Http::response('OK'),
    ]);

    $status = (new DockerRuntime(containerRuntimeEngine()))->ping();

    expect($status->reachable)->toBeFalse()
        ->and($status->message)->toContain('rejected API version')
        ->and($status->message)->toContain('CONTAINER_API_VERSION');
});

it('reports an unreachable endpoint', function (): void {
    Http::fake(['*' => Http::response('no route', 500)]);

    $status = (new DockerRuntime(containerRuntimeEngine()))->ping();

    expect($status->reachable)->toBeFalse()
        ->and($status->message)->toContain('is not reachable');
});

it('translates a definition into the engine create payload', function (): void {
    Http::fake([
        '*/containers/create*'           => Http::response(['Id' => 'container-id']),
        '*/containers/container-id/json' => Http::response(containerInspectBody()),
    ]);

    $info = (new DockerRuntime(containerRuntimeEngine()))->create(containerRuntimeDefinition());

    expect($info->state)->toBe(ContainerState::Running);

    Http::assertSent(function (Request $request): bool {
        if ( ! str_contains($request->url(), '/containers/create')) {
            return false;
        }

        expect($request->url())->toContain('name=vendra-storefront-flowers')
            ->and($request['Image'])->toBe('ghcr.io/misaf/storefront:1.0.0')
            ->and($request['Env'])->toBe(['STORE_ID=101', 'DOMAIN=flowers-a.com'])
            ->and($request['Labels'])->toBe(['io.vendra.managed-by' => 'vendra'])
            ->and($request['ExposedPorts'])->toHaveKey('3000/tcp')
            ->and($request['Healthcheck']['Interval'])->toBe(10_000_000_000)
            ->and($request['Healthcheck']['StartPeriod'])->toBe(15_000_000_000)
            ->and($request['HostConfig']['RestartPolicy']['Name'])->toBe('unless-stopped')
            ->and($request['HostConfig']['SecurityOpt'])->toBe(['no-new-privileges:true'])
            ->and($request['HostConfig']['Binds'])->toBe(['/srv/certs:/certs:ro'])
            ->and($request['HostConfig']['NetworkMode'])->toBe('traefik-public')
            ->and($request['HostConfig']['LogConfig'])->toBe(['Type' => 'json-file', 'Config' => ['max-size' => '10m']])
            ->and($request['NetworkingConfig']['EndpointsConfig'])->toHaveKey('traefik-public');

        return true;
    });
});

it('publishes only the ports asked for', function (): void {
    Http::fake([
        '*/containers/create*'           => Http::response(['Id' => 'container-id']),
        '*/containers/container-id/json' => Http::response(containerInspectBody()),
    ]);

    (new DockerRuntime(containerRuntimeEngine()))->create(new ContainerDefinition(
        name: 'published',
        image: new ImageReference('nginx:1.27'),
        ports: [new PortBinding(80, hostPort: 8080), new PortBinding(443)],
    ));

    Http::assertSent(function (Request $request): bool {
        if ( ! str_contains($request->url(), '/containers/create')) {
            return false;
        }

        expect($request['ExposedPorts'])->toHaveKeys(['80/tcp', '443/tcp'])
            ->and($request['HostConfig']['PortBindings'])->toBe(['80/tcp' => [['HostPort' => '8080']]]);

        return true;
    });
});

it('caps a container with the engine\'s own resource keys', function (): void {
    Http::fake([
        '*/containers/create*'           => Http::response(['Id' => 'container-id']),
        '*/containers/*/json'            => Http::response(['Id' => 'container-id', 'Name' => '/vendra-storefront-flowers', 'State' => ['Status' => 'created']]),
    ]);

    new DockerRuntime(containerRuntimeEngine())->create(new ContainerDefinition(
        name: 'vendra-storefront-capped',
        image: new ImageReference('ghcr.io/misaf/storefront:1.0.0'),
        resources: new ResourceLimits(cpus: 0.5, memoryMegabytes: 512),
    ));

    Http::assertSent(function (Request $request): bool {
        if ( ! str_contains($request->url(), '/containers/create')) {
            return false;
        }

        expect($request['HostConfig']['NanoCpus'])->toBe(500_000_000)
            ->and($request['HostConfig']['Memory'])->toBe(536_870_912)
            ->and($request['HostConfig'])->not->toHaveKey('MemoryReservation');

        return true;
    });
});

it('leaves the resource keys out entirely when nothing is capped', function (): void {
    Http::fake([
        '*/containers/create*'           => Http::response(['Id' => 'container-id']),
        '*/containers/*/json'            => Http::response(['Id' => 'container-id', 'Name' => '/vendra-storefront-flowers', 'State' => ['Status' => 'created']]),
    ]);

    new DockerRuntime(containerRuntimeEngine())->create(containerRuntimeDefinition());

    Http::assertSent(function (Request $request): bool {
        if ( ! str_contains($request->url(), '/containers/create')) {
            return false;
        }

        expect($request['HostConfig'])->not->toHaveKeys(['NanoCpus', 'Memory', 'MemoryReservation']);

        return true;
    });
});

it('omits the log block when no driver is configured', function (): void {
    Http::fake([
        '*/containers/create*'           => Http::response(['Id' => 'container-id']),
        '*/containers/container-id/json' => Http::response(containerInspectBody()),
    ]);

    (new DockerRuntime(containerRuntimeEngine()))->create(
        containerRuntimeDefinition(new LogConfiguration('')),
    );

    Http::assertSent(function (Request $request): bool {
        if ( ! str_contains($request->url(), '/containers/create')) {
            return false;
        }

        expect($request['HostConfig'])->not->toHaveKey('LogConfig');

        return true;
    });
});

it('fails a create with the runtime own message', function (): void {
    Http::fake(['*/containers/create*' => Http::response(['message' => 'no such image'], 404)]);

    expect(fn() => (new DockerRuntime(containerRuntimeEngine()))->create(containerRuntimeDefinition()))
        ->toThrow(ContainerRuntimeException::class, 'no such image');
});

it('treats an already running container as started', function (): void {
    Http::fake(['*/containers/*/start' => Http::response('', 304)]);

    (new DockerRuntime(containerRuntimeEngine()))->start(new ContainerId('flowers'));

    Http::assertSentCount(1);
});

it('treats an already stopped container as stopped', function (): void {
    Http::fake(['*/containers/*/stop' => Http::response('', 304)]);

    (new DockerRuntime(containerRuntimeEngine()))->stop(new ContainerId('flowers'));

    Http::assertSentCount(1);
});

it('treats an absent container as removed', function (): void {
    Http::fake(['*' => Http::response(['message' => 'no such container'], 404)]);

    (new DockerRuntime(containerRuntimeEngine()))->remove(new ContainerId('flowers'));

    Http::assertSent(fn(Request $request): bool => str_contains($request->url(), 'force=true')
        && str_contains($request->url(), 'v=true'));
});

it('fails a removal the runtime refuses', function (): void {
    Http::fake(['*' => Http::response(['message' => 'device busy'], 500)]);

    expect(fn() => (new DockerRuntime(containerRuntimeEngine()))->remove(new ContainerId('flowers')))
        ->toThrow(ContainerRuntimeException::class, 'device busy');
});

it('returns null for a container that does not exist', function (): void {
    Http::fake(['*' => Http::response(['message' => 'no such container'], 404)]);

    $runtime = new DockerRuntime(containerRuntimeEngine());

    expect($runtime->find(new ContainerId('missing')))->toBeNull()
        ->and(fn() => $runtime->inspect(new ContainerId('missing')))
        ->toThrow(ContainerNotFoundException::class);
});

it('splits the reference the engine pulls by', function (): void {
    Http::fake([
        '*/images/create*' => Http::response(''),
        '*/images/*/json'  => Http::response(['RepoDigests' => ['ghcr.io/misaf/storefront@sha256:pulled']]),
    ]);

    $info = (new DockerRuntime(containerRuntimeEngine()))->pull(new ImageReference('ghcr.io/misaf/storefront:1.0.0'));

    expect($info->digest)->toBe('sha256:pulled');

    Http::assertSent(fn(Request $request): bool => str_contains($request->url(), 'fromImage=' . urlencode('ghcr.io/misaf/storefront'))
        && str_contains($request->url(), 'tag=1.0.0'));
});

it('fails a pull the engine reports inside a successful response', function (): void {
    Http::fake([
        '*/images/create*' => Http::response(
            '{"status":"Pulling"}' . "\n" . '{"error":"manifest unknown"}',
        ),
    ]);

    expect(fn() => (new DockerRuntime(containerRuntimeEngine()))->pull(new ImageReference('ghcr.io/misaf/storefront:1.0.0')))
        ->toThrow(ContainerRuntimeException::class, 'manifest unknown');
});

it('reads logs and strips the stream multiplexing headers', function (): void {
    $frame = static fn(string $line): string => pack('C4N', 1, 0, 0, 0, mb_strlen($line, '8bit')) . $line;

    Http::fake(['*/containers/*/logs*' => Http::response($frame("first\n") . $frame("second\n"))]);

    $logs = (new DockerRuntime(containerRuntimeEngine()))->logs(new ContainerId('flowers'), lines: 50);

    expect($logs->lines())->toBe(['first', 'second']);

    Http::assertSent(fn(Request $request): bool => str_contains($request->url(), 'tail=50'));
});

it('finds and creates networks', function (): void {
    Http::fake([
        '*/networks/create'         => Http::response(['Id' => 'net-id']),
        '*/networks/traefik-public' => Http::response(['Name' => 'traefik-public', 'Id' => 'net-id', 'Driver' => 'bridge']),
        '*/networks/*'              => Http::response(['message' => 'not found'], 404),
    ]);

    $runtime = new DockerRuntime(containerRuntimeEngine());

    expect($runtime->findNetwork('traefik-public')?->driver)->toBe('bridge')
        ->and($runtime->findNetwork('absent'))->toBeNull()
        ->and($runtime->createNetwork(new NetworkDefinition('traefik-public'))->name)->toBe('traefik-public');
});

it('serves a unix socket endpoint over a placeholder host', function (): void {
    Http::fake(['*' => Http::response('OK')]);

    (new DockerRuntime(containerRuntimeEngine('unix:///var/run/docker.sock')))->ping();

    Http::assertSent(fn(Request $request): bool => str_starts_with($request->url(), 'http://container-runtime/v1.43/_ping'));
});

describe('podman', function (): void {
    it('names itself in its runtime status', function (): void {
        Http::fake(['*' => Http::response('OK')]);

        expect((new PodmanRuntime(containerRuntimeEngine()))->ping()->runtime)->toBe('podman');
    });

    it('drops log options for a driver it does not use', function (): void {
        Http::fake([
            '*/containers/create*'           => Http::response(['Id' => 'container-id']),
            '*/containers/container-id/json' => Http::response(containerInspectBody()),
        ]);

        (new PodmanRuntime(containerRuntimeEngine()))->create(
            containerRuntimeDefinition(new LogConfiguration('syslog', ['max-size' => '10m'])),
        );

        Http::assertSent(function (Request $request): bool {
            if ( ! str_contains($request->url(), '/containers/create')) {
                return false;
            }

            expect($request['HostConfig']['LogConfig'])->toBe(['Type' => 'syslog']);

            return true;
        });
    });

    it('keeps options for a driver it does use', function (): void {
        Http::fake([
            '*/containers/create*'           => Http::response(['Id' => 'container-id']),
            '*/containers/container-id/json' => Http::response(containerInspectBody()),
        ]);

        (new PodmanRuntime(containerRuntimeEngine()))->create(
            containerRuntimeDefinition(new LogConfiguration('k8s-file', ['max-size' => '10m'])),
        );

        Http::assertSent(function (Request $request): bool {
            if ( ! str_contains($request->url(), '/containers/create')) {
                return false;
            }

            expect($request['HostConfig']['LogConfig']['Config'])->toBe(['max-size' => '10m']);

            return true;
        });
    });
});

describe('engine identity', function (): void {
    it('reports the endpoint and the engine that answered', function (): void {
        Http::fake(['*' => Http::response('OK', headers: ['Server' => 'Docker/29.7.2 (linux)'])]);

        $status = (new DockerRuntime(containerRuntimeEngine('unix:///var/run/docker.sock')))->ping();

        expect($status->endpoint)->toBe('unix:///var/run/docker.sock')
            ->and($status->reportedEngine())->toBe(ContainerEngine::Docker)
            ->and($status->engineMismatch())->toBeFalse()
            ->and($status->describeDaemon())->toBe('unix:///var/run/docker.sock (Docker/29.7.2 (linux))');
    });

    it('detects a daemon that is not the configured runtime', function (): void {
        Http::fake(['*' => Http::response('OK', headers: ['Server' => 'Libpod/5.8.6 (linux)'])]);

        $status = (new DockerRuntime(containerRuntimeEngine('unix:///var/run/docker.sock')))->ping();

        expect($status->reportedEngine())->toBe(ContainerEngine::Podman)
            ->and($status->engineMismatch())->toBeTrue();
    });

    it('reads podman answering its own compatibility socket as agreement', function (): void {
        Http::fake(['*' => Http::response('OK', headers: ['Server' => 'Libpod/5.8.6 (linux)'])]);

        expect((new PodmanRuntime(containerRuntimeEngine()))->ping()->engineMismatch())->toBeFalse();
    });

    it('treats an unrecognised or absent server header as no evidence of a mismatch', function (): void {
        Http::fake(['*' => Http::response('OK', headers: ['Server' => 'nginx/1.27'])]);

        $unrecognised = (new DockerRuntime(containerRuntimeEngine()))->ping();

        expect($unrecognised->reportedEngine())->toBeNull()
            ->and($unrecognised->engineMismatch())->toBeFalse();

        Http::fake(['*' => Http::response('OK')]);

        expect((new DockerRuntime(containerRuntimeEngine()))->ping()->engineMismatch())->toBeFalse();
    });

    it('does not claim a mismatch on a daemon that never answered', function (): void {
        Http::fake(['*' => Http::response(['message' => 'no such file'], 500)]);

        $status = (new DockerRuntime(containerRuntimeEngine()))->ping();

        expect($status->reachable)->toBeFalse()
            ->and($status->engineMismatch())->toBeFalse()
            ->and($status->endpoint)->toBe('tcp://runtime.test:2375');
    });
});
