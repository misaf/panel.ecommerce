<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Contracts;

use Misaf\VendraContainer\Exceptions\ContainerNotFoundException;
use Misaf\VendraContainer\Exceptions\ContainerRuntimeException;
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
 * The only way anything above this package talks to a container runtime.
 *
 * Both sides of every method are typed values, so a caller describes *what*
 * should run and the adapter decides how its runtime is told to do it. Nothing
 * here mentions sockets, HTTP paths, or a daemon's JSON — that is the point:
 * swapping Docker for Podman is a binding, not a change to any caller.
 *
 * Implementations normalise their failures to
 * {@see ContainerRuntimeException}, so callers never parse a
 * runtime-specific error body.
 */
interface ContainerRuntime
{
    /**
     * Whether the runtime answers, and which runtime it is.
     *
     * Reports rather than throws, so a caller can degrade — record the intent,
     * reconcile later — instead of failing outright on an estate that is not up
     * yet.
     */
    public function ping(): RuntimeStatus;

    /**
     * Fetch an image so a later create does not depend on registry latency.
     *
     * @throws ContainerRuntimeException when the pull fails, including a failure
     *                                   the runtime reports mid-stream
     */
    public function pull(ImageReference $image): ImageInfo;

    /**
     * What the runtime knows about an image it already holds, or null.
     */
    public function inspectImage(ImageReference $image): ?ImageInfo;

    /**
     * Create a container from its definition, without starting it.
     *
     * @throws ContainerRuntimeException
     */
    public function create(ContainerDefinition $definition): ContainerInfo;

    /**
     * Start a container. Already running is success.
     *
     * @throws ContainerRuntimeException
     */
    public function start(ContainerId $container): void;

    /**
     * Stop a container. Already stopped is success.
     *
     * @throws ContainerRuntimeException
     */
    public function stop(ContainerId $container): void;

    /**
     * @throws ContainerRuntimeException
     */
    public function restart(ContainerId $container): void;

    /**
     * Remove a container and its anonymous volumes. Absent is success.
     *
     * @throws ContainerRuntimeException
     */
    public function remove(ContainerId $container): void;

    /**
     * @throws ContainerNotFoundException when no such container exists
     * @throws ContainerRuntimeException
     */
    public function inspect(ContainerId $container): ContainerInfo;

    /**
     * The container, or null when it does not exist.
     *
     * The idempotent half of {@see inspect()}: provisioning asks "is it already
     * there?" far more often than it asserts that it must be.
     */
    public function find(ContainerId $container): ?ContainerInfo;

    /**
     * @throws ContainerRuntimeException
     */
    public function logs(ContainerId $container, int $lines = 200): ContainerLogs;

    /**
     * The network, or null when it does not exist.
     */
    public function findNetwork(string $name): ?NetworkInfo;

    /**
     * @throws ContainerRuntimeException
     */
    public function createNetwork(NetworkDefinition $definition): NetworkInfo;
}
