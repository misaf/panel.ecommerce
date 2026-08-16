<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Misaf\VendraContainer\Enums\ContainerHealth;
use Misaf\VendraContainer\Exceptions\ContainerRuntimeException;
use Misaf\VendraContainer\Support\ContainerHealthGate;
use Misaf\VendraContainer\Testing\FakeContainerRuntime;
use Misaf\VendraContainer\ValueObjects\ContainerDefinition;
use Misaf\VendraContainer\ValueObjects\HealthCheck;
use Misaf\VendraContainer\ValueObjects\ImageReference;

function healthGateDefinition(bool $withHealthCheck = true): ContainerDefinition
{
    return new ContainerDefinition(
        name: 'gated',
        image: new ImageReference('ghcr.io/misaf/storefront:1.0.0'),
        healthCheck: $withHealthCheck ? new HealthCheck(['CMD', 'true']) : null,
    );
}

it('passes a container that reports healthy', function (): void {
    $runtime = new FakeContainerRuntime();
    $definition = healthGateDefinition();

    $runtime->create($definition);
    $runtime->start($definition->id());

    expect(app(ContainerHealthGate::class)->awaitDefinition($runtime, $definition, 5))->toBeTrue();
});

it('degrades to running and warns when a requested health check never reports', function (): void {
    Log::spy();

    $runtime = (new FakeContainerRuntime())->reportingHealth(ContainerHealth::None);
    $definition = healthGateDefinition();

    $runtime->create($definition);
    $runtime->start($definition->id());

    expect(app(ContainerHealthGate::class)->awaitDefinition($runtime, $definition, 5))->toBeTrue();

    Log::shouldHaveReceived('warning')
        ->once()
        ->withArgs(fn(string $message): bool => str_contains($message, 'no health state'));
});

it('stays quiet when no health check was asked for', function (): void {
    Log::spy();

    $runtime = (new FakeContainerRuntime())->reportingHealth(ContainerHealth::None);
    $definition = healthGateDefinition(withHealthCheck: false);

    $runtime->create($definition);
    $runtime->start($definition->id());

    expect(app(ContainerHealthGate::class)->awaitDefinition($runtime, $definition, 5))->toBeTrue();

    Log::shouldNotHaveReceived('warning');
});

it('fails immediately when the container exits while starting', function (): void {
    $runtime = (new FakeContainerRuntime())->failingOnStart('gated', exitCode: 137);
    $definition = healthGateDefinition();

    $runtime->create($definition);
    $runtime->start($definition->id());

    expect(fn(): bool => app(ContainerHealthGate::class)->awaitDefinition($runtime, $definition, 5))
        ->toThrow(ContainerRuntimeException::class, 'exited while starting with code 137');
});

it('gives up at the deadline while the container is still starting', function (): void {
    $runtime = (new FakeContainerRuntime())->reportingHealth(ContainerHealth::Starting);
    $definition = healthGateDefinition();

    $runtime->create($definition);
    $runtime->start($definition->id());

    expect(app(ContainerHealthGate::class)->await($runtime, $definition->id(), 0))->toBeFalse();
});
