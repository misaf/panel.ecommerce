<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Support;

use Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema;
use Misaf\VendraSupport\Contracts\AttributeApiResolver;

final class AttributeApiServiceResolver implements AttributeApiResolver
{
    public function isAvailable(): bool
    {
        return true;
    }

    public function attributeValueSchema(): ?string
    {
        return AttributeValueSchema::class;
    }
}
