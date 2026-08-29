<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Support;

final readonly class StorefrontNetwork
{
    public function __construct(
        public string $name,
        public ?string $driver = null,
    ) {}
}
