<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Runtimes;

/**
 * Docker, over its Engine API.
 *
 * Everything it does is inherited: Docker is the API the shared runtime targets,
 * so this exists to be a name a binding can point at.
 */
final class DockerRuntime extends DockerCompatibleRuntime
{
    public const string NAME = 'docker';

    public const string DEFAULT_API_VERSION = 'v1.43';

    protected function runtimeName(): string
    {
        return self::NAME;
    }
}
