<?php

declare(strict_types=1);

use Misaf\VendraContainer\Enums\ContainerHealth;
use Misaf\VendraContainer\Enums\ContainerState;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\ContainerInfo;
use Misaf\VendraContainer\ValueObjects\ContainerLogs;
use Misaf\VendraContainer\ValueObjects\EnvironmentVariable;
use Misaf\VendraContainer\ValueObjects\ImageInfo;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\PortBinding;
use Misaf\VendraContainer\ValueObjects\ResourceLimits;
use Misaf\VendraContainer\ValueObjects\VolumeMount;

describe('ImageReference', function (): void {
    it('splits a repository and tag', function (): void {
        $image = new ImageReference('ghcr.io/misaf/vendra-storefront-florist:1.2.0');

        expect($image->repository)->toBe('ghcr.io/misaf/vendra-storefront-florist')
            ->and($image->tag)->toBe('1.2.0')
            ->and($image->digest)->toBeNull()
            ->and($image->isPinned())->toBeFalse();
    });

    it('defaults an untagged reference to latest', function (): void {
        expect((new ImageReference('nginx'))->tag)->toBe('latest');
    });

    it('does not mistake a registry port for a tag', function (): void {
        $image = new ImageReference('registry.internal:5000/vendra/storefront');

        expect($image->repository)->toBe('registry.internal:5000/vendra/storefront')
            ->and($image->tag)->toBe('latest');
    });

    it('keeps a digest-pinned reference whole', function (): void {
        $image = new ImageReference('ghcr.io/misaf/storefront@sha256:abc123');

        expect($image->repository)->toBe('ghcr.io/misaf/storefront')
            ->and($image->tag)->toBe('')
            ->and($image->digest)->toBe('sha256:abc123')
            ->and($image->isPinned())->toBeTrue();
    });

    it('rejects an empty reference', function (): void {
        expect(fn(): ImageReference => new ImageReference('  '))
            ->toThrow(InvalidArgumentException::class);
    });
});

describe('ContainerId', function (): void {
    it('rejects a blank handle', function (): void {
        expect(fn(): ContainerId => new ContainerId(''))
            ->toThrow(InvalidArgumentException::class);
    });

    it('compares by value', function (): void {
        expect(ContainerId::fromName('storefront-101')->equals(new ContainerId('storefront-101')))->toBeTrue()
            ->and(ContainerId::fromName('storefront-101')->equals(new ContainerId('storefront-102')))->toBeFalse();
    });
});

describe('EnvironmentVariable', function (): void {
    it('renders as a KEY=VALUE pair', function (): void {
        expect((string) new EnvironmentVariable('STORE_ID', '101'))->toBe('STORE_ID=101');
    });

    it('keeps an equals sign inside the value', function (): void {
        expect((string) new EnvironmentVariable('CONFIG', 'a=b'))->toBe('CONFIG=a=b');
    });

    it('builds a collection from scalars', function (): void {
        $variables = EnvironmentVariable::collection([
            'STORE_ID'    => 101,
            'DEBUG'       => false,
            'OPTIONAL'    => null,
        ]);

        expect(array_map(strval(...), $variables))->toBe([
            'STORE_ID=101',
            'DEBUG=false',
            'OPTIONAL=',
        ]);
    });

    it('rejects a blank name', function (): void {
        expect(fn(): EnvironmentVariable => new EnvironmentVariable(' ', 'x'))
            ->toThrow(InvalidArgumentException::class);
    });
});

describe('VolumeMount and PortBinding', function (): void {
    it('renders a read-only bind', function (): void {
        expect(VolumeMount::readOnly('/srv/certs', '/certs')->toBind())->toBe('/srv/certs:/certs:ro')
            ->and((new VolumeMount('/srv/data', '/data'))->toBind())->toBe('/srv/data:/data');
    });

    it('keys a port by protocol and reports whether it is published', function (): void {
        expect((new PortBinding(3000))->key())->toBe('3000/tcp')
            ->and((new PortBinding(3000))->isPublished())->toBeFalse()
            ->and((new PortBinding(3000, hostPort: 8080))->isPublished())->toBeTrue();
    });

    it('rejects an impossible port', function (): void {
        expect(fn(): PortBinding => new PortBinding(0))->toThrow(InvalidArgumentException::class);
    });
});

describe('ContainerInfo', function (): void {
    it('reduces an engine inspect body to a typed value', function (): void {
        $info = ContainerInfo::fromEnginePayload([
            'Id'    => 'abc123',
            'Name'  => '/vendra-storefront-flowers',
            'Image' => 'sha256:deadbeef',
            'State' => [
                'Status'   => 'running',
                'ExitCode' => 0,
                'Health'   => ['Status' => 'healthy'],
            ],
            'Config' => [
                'Image'  => 'ghcr.io/misaf/storefront:1.0.0',
                'Labels' => ['io.vendra.managed-by' => 'vendra'],
            ],
        ]);

        expect($info->name)->toBe('vendra-storefront-flowers')
            ->and($info->state)->toBe(ContainerState::Running)
            ->and($info->health)->toBe(ContainerHealth::Healthy)
            ->and($info->isRunning())->toBeTrue()
            ->and($info->isServing())->toBeTrue()
            ->and($info->hasLabel('io.vendra.managed-by', 'vendra'))->toBeTrue()
            ->and($info->hasLabel('io.vendra.managed-by', 'somebody-else'))->toBeFalse();
    });

    it('treats a running container with no health state as serving', function (): void {
        $info = ContainerInfo::fromEnginePayload([
            'Id'    => 'abc123',
            'Name'  => '/plain',
            'State' => ['Status' => 'running'],
        ]);

        expect($info->health)->toBe(ContainerHealth::None)
            ->and($info->isServing())->toBeTrue();
    });

    it('does not treat an unhealthy container as serving', function (): void {
        $info = ContainerInfo::fromEnginePayload([
            'Id'    => 'abc123',
            'Name'  => '/sick',
            'State' => ['Status' => 'running', 'Health' => ['Status' => 'unhealthy']],
        ]);

        expect($info->isServing())->toBeFalse();
    });

    it('maps an unknown status rather than failing', function (): void {
        $info = ContainerInfo::fromEnginePayload([
            'Id'    => 'abc123',
            'Name'  => '/odd',
            'State' => ['Status' => 'something-new'],
        ]);

        expect($info->state)->toBe(ContainerState::Unknown)
            ->and($info->state->hasStopped())->toBeFalse();
    });

    it('reports an exit code for a stopped container', function (): void {
        $info = ContainerInfo::fromEnginePayload([
            'Id'    => 'abc123',
            'Name'  => '/gone',
            'State' => ['Status' => 'exited', 'ExitCode' => 137],
        ]);

        expect($info->state->hasStopped())->toBeTrue()
            ->and($info->exitCode)->toBe(137);
    });
});

describe('ImageInfo', function (): void {
    it('reads the registry digest rather than the local image id', function (): void {
        $info = ImageInfo::fromEnginePayload(new ImageReference('ghcr.io/misaf/storefront:1.0.0'), [
            'Id'          => 'sha256:localconfighash',
            'RepoDigests' => ['ghcr.io/misaf/storefront@sha256:registrydigest'],
            'RepoTags'    => ['ghcr.io/misaf/storefront:1.0.0'],
        ]);

        expect($info->digest)->toBe('sha256:registrydigest')
            ->and($info->id)->toBe('sha256:localconfighash')
            ->and($info->tags)->toBe(['ghcr.io/misaf/storefront:1.0.0']);
    });

    it('prefers the digest a pinned reference already carries', function (): void {
        $info = ImageInfo::fromEnginePayload(new ImageReference('ghcr.io/misaf/storefront@sha256:pinned'), []);

        expect($info->digest)->toBe('sha256:pinned');
    });
});

describe('ContainerLogs', function (): void {
    it('splits output into non-empty lines', function (): void {
        $logs = new ContainerLogs(new ContainerId('x'), "first\n\nsecond\n");

        expect($logs->lines())->toBe(['first', 'second'])
            ->and($logs->isEmpty())->toBeFalse()
            ->and((new ContainerLogs(new ContainerId('x'), ''))->isEmpty())->toBeTrue();
    });
});

describe('ResourceLimits', function (): void {
    it('translates operator units into the engine\'s', function (): void {
        $limits = new ResourceLimits(cpus: 0.5, memoryMegabytes: 512, memoryReservationMegabytes: 256);

        expect($limits->nanoCpus())->toBe(500_000_000)
            ->and($limits->memoryBytes())->toBe(536_870_912)
            ->and($limits->memoryReservationBytes())->toBe(268_435_456)
            ->and($limits->isConfigured())->toBeTrue();
    });

    it('leaves an unset limit uncapped', function (): void {
        $limits = new ResourceLimits(memoryMegabytes: 256);

        expect($limits->nanoCpus())->toBeNull()
            ->and($limits->memoryReservationBytes())->toBeNull()
            ->and($limits->memoryBytes())->toBe(268_435_456);
    });

    it('reports an entirely empty set as unconfigured', function (): void {
        expect((new ResourceLimits())->isConfigured())->toBeFalse();
    });

    it('rejects limits that cannot mean anything', function (): void {
        expect(fn(): ResourceLimits => new ResourceLimits(cpus: 0))
            ->toThrow(InvalidArgumentException::class)
            ->and(fn(): ResourceLimits => new ResourceLimits(memoryMegabytes: -1))
            ->toThrow(InvalidArgumentException::class);
    });

    it('rejects a reservation above the limit it reserves within', function (): void {
        expect(fn(): ResourceLimits => new ResourceLimits(memoryMegabytes: 256, memoryReservationMegabytes: 512))
            ->toThrow(InvalidArgumentException::class);
    });
});
