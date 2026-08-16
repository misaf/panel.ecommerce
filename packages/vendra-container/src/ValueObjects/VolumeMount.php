<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use InvalidArgumentException;

/**
 * A host path or named volume made visible inside the container.
 */
final class VolumeMount
{
    public function __construct(
        public readonly string $source,
        public readonly string $target,
        public readonly bool $readOnly = false,
    ) {
        if ('' === mb_trim($source) || '' === mb_trim($target)) {
            throw new InvalidArgumentException('A volume mount needs both a source and a target.');
        }
    }

    public static function readOnly(string $source, string $target): self
    {
        return new self($source, $target, readOnly: true);
    }

    /**
     * The Engine's bind string, e.g. "/srv/certs:/certs:ro".
     */
    public function toBind(): string
    {
        return $this->source . ':' . $this->target . ($this->readOnly ? ':ro' : '');
    }
}
