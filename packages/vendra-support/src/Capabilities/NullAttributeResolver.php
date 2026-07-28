<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Capabilities;

use Misaf\VendraSupport\Contracts\AttributeResolver;

final class NullAttributeResolver implements AttributeResolver
{
    public function available(): bool
    {
        return false;
    }

    public function valueModel(): ?string
    {
        return null;
    }

    public function options(): array
    {
        return [];
    }
}
