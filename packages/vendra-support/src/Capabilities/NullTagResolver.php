<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Capabilities;

use Misaf\VendraSupport\Contracts\TagResolver;
use Misaf\VendraSupport\Support\TagRelationship;

final class NullTagResolver implements TagResolver
{
    public function available(): bool
    {
        return false;
    }

    public function relationship(): ?TagRelationship
    {
        return null;
    }
}
