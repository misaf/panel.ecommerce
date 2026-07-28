<?php

declare(strict_types=1);

namespace Misaf\VendraApi\ApiResource;

final readonly class ResourceReference
{
    public function __construct(
        public int|string $id,
        public string $type,
        public ?string $label = null,
    ) {}
}
