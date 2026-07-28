<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Support;

use Misaf\VendraAttributeApi\ApiResource\AttributeValueResource;
use Misaf\VendraSupport\Contracts\AttributeApiResolver;

final class AttributeApiServiceResolver implements AttributeApiResolver
{
    public function isAvailable(): bool
    {
        return true;
    }

    public function attributeOptionResource(): ?string
    {
        return AttributeValueResource::class;
    }
}
