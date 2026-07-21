<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Misaf\VendraSupport\Contracts\AttributeApiResolver;

final class NullAttributeApiResolver implements AttributeApiResolver
{
    public function isAvailable(): bool
    {
        return false;
    }

    public function attributeValueSchema(): ?string
    {
        return null;
    }
}
