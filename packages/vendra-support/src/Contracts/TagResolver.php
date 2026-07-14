<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

use Misaf\VendraSupport\Support\TagRelationship;

interface TagResolver
{
    public function available(): bool;

    public function relationship(): ?TagRelationship;
}
