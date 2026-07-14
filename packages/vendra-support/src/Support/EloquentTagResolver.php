<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Misaf\VendraSupport\Contracts\TagResolver;

final readonly class EloquentTagResolver implements TagResolver
{
    public function __construct(private TagRelationship $relationship) {}

    public function available(): bool
    {
        return true;
    }

    public function relationship(): TagRelationship
    {
        return $this->relationship;
    }
}
