<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Misaf\VendraAttribute\Models\AttributeValue;

trait HasAttributeValues
{
    /** @return MorphMany<AttributeValue, $this> */
    public function attributeValues(): MorphMany
    {
        return $this->morphMany(AttributeValue::class, 'attributable');
    }
}
