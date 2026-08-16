<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\Enums;

use Illuminate\Support\Str;

/**
 * Which engine is at the other end of an endpoint.
 *
 * The configuration states an intent and the daemon states a fact, and this is
 * the vocabulary both are expressed in so the two can be compared at all. It
 * exists apart from the runtime adapters because a value object may not depend
 * on them, and because identifying an engine is not the same act as driving one.
 */
enum ContainerEngine: string
{
    case Docker = 'docker';
    case Podman = 'podman';

    /**
     * The engine a daemon claims to be, from the `Server` header it answers with.
     *
     * Docker sends "Docker/<version>" and Podman's compatibility socket sends
     * "Libpod/<version>". Anything else is null rather than a guess: a proxy or
     * an unfamiliar engine in front of the socket is not evidence about which
     * one it is.
     */
    public static function fromServerHeader(?string $header): ?self
    {
        if (null === $header) {
            return null;
        }

        return match (true) {
            Str::contains($header, 'libpod', ignoreCase: true) => self::Podman,
            Str::contains($header, 'docker', ignoreCase: true) => self::Docker,
            default                                            => null,
        };
    }
}
