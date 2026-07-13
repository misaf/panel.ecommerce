<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AttributeResolver
{
    public function available(): bool;

    /** @return class-string<Model>|null */
    public function valueModel(): ?string;

    /** @return array<int|string, string> */
    public function options(): array;
}
