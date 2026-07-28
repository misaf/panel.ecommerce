<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\Support;

use Misaf\VendraAttributeApi\ApiResource\CatalogOption;
use Misaf\VendraSupport\Contracts\AttributeApiResolver;

final class AttributeApiServiceResolver implements AttributeApiResolver
{
    public function isAvailable(): bool
    {
        return true;
    }

    public function attributeOptionResource(): ?string
    {
        return CatalogOption::class;
    }
}
