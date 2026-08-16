<?php

declare(strict_types=1);

use Misaf\VendraContainer\Enums\ContainerState;
use Misaf\VendraContainer\Exceptions\ContainerNotFoundException;
use Misaf\VendraContainer\Testing\FakeContainerRuntime;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\ContainerId;
use Misaf\VendraContainer\ValueObjects\ImageReference;
use Misaf\VendraContainer\ValueObjects\NetworkDefinition;

function fakeRuntimeDefinition(string $name = 'storefront-101'): ContainerDefinition
{
    return new ContainerDefinition(
        name: $name,
        image: new ImageReference('ghcr.io/misaf/storefront:1.0.0'),
        labels: ['io.vendra.managed-by' => 'vendra'],
    );
}

it('records what was asked of it', function (): void {
    $runtime = new FakeContainerRuntime();
    $definition = fakeRuntimeDefinition();

    $runtime->create($definition);
    $runtime->start($definition->id());
    $runtime->remove($definition->id());

    expect($runtime->calls)->toBe([
        'create:storefront-101',
        'start:storefront-101',
        'remove:storefront-101',
    ])->and($runtime->has('storefront-101'))->toBeFalse();
});

it('keeps the definition a container was created from', function (): void {
    $runtime = new FakeContainerRuntime();

    $runtime->create(fakeRuntimeDefinition());

    expect($runtime->definitionFor('storefront-101')?->labels)
        ->toBe(['io.vendra.managed-by' => 'vendra']);
});

it('follows the contract for absent containers', function (): void {
    $runtime = new FakeContainerRuntime();

    $runtime->remove(new ContainerId('never-existed'));

    expect($runtime->find(new ContainerId('never-existed')))->toBeNull()
        ->and(fn() => $runtime->inspect(new ContainerId('never-existed')))
        ->toThrow(ContainerNotFoundException::class);
});

it('moves a container through its lifecycle', function (): void {
    $runtime = new FakeContainerRuntime();
    $definition = fakeRuntimeDefinition();

    expect($runtime->create($definition)->state)->toBe(ContainerState::Created);

    $runtime->start($definition->id());

    expect($runtime->inspect($definition->id())->isRunning())->toBeTrue();

    $runtime->stop($definition->id());

    expect($runtime->inspect($definition->id())->state)->toBe(ContainerState::Exited);

    $runtime->restart($definition->id());

    expect($runtime->inspect($definition->id())->isRunning())->toBeTrue();
});

it('answers network lookups', function (): void {
    $runtime = (new FakeContainerRuntime())->withNetwork('traefik-public');

    expect($runtime->findNetwork('traefik-public'))->not->toBeNull()
        ->and($runtime->findNetwork('absent'))->toBeNull()
        ->and($runtime->createNetwork(new NetworkDefinition('absent'))->name)->toBe('absent')
        ->and($runtime->findNetwork('absent'))->not->toBeNull();
});

it('can be made unreachable', function (): void {
    expect((new FakeContainerRuntime())->unreachable()->ping()->reachable)->toBeFalse()
        ->and((new FakeContainerRuntime())->ping()->reachable)->toBeTrue();
});

it('records pulls and reports a digest', function (): void {
    $runtime = new FakeContainerRuntime();

    $info = $runtime->pull(new ImageReference('ghcr.io/misaf/storefront@sha256:pinned'));

    expect($info->digest)->toBe('sha256:pinned')
        ->and($runtime->pulled)->toHaveCount(1);
});
