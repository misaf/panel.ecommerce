<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Capabilities;

use Misaf\VendraSupport\Contracts\AttributeApiResolver;

final class NullAttributeApiResolver implements AttributeApiResolver
{
    public function isAvailable(): bool
    {
        return false;
    }

    public function attributeOptionResource(): ?string
    {
        return null;
    }
}
