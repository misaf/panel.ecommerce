<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Misaf\VendraSupport\Contracts\TagResolver;

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
