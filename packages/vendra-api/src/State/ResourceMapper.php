<?php

declare(strict_types=1);

namespace Misaf\VendraApi\State;

use Illuminate\Database\Eloquent\Model;

/**
 * Transforms an Eloquent model into its API resource representation.
 *
 * Used by EloquentResourceProvider to keep model-to-resource mapping explicit.
 */
interface ResourceMapper
{
    public function map(Model $model): object;
}
