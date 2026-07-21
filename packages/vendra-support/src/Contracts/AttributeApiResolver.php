<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

interface AttributeApiResolver
{
    public function isAvailable(): bool;

    /** @return class-string|null */
    public function attributeValueSchema(): ?string;
}
