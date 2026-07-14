<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use LogicException;
use Misaf\VendraSupport\Support\TagIntegration;

trait HasOptionalTags
{
    /** @return MorphToMany<Model, $this> */
    public function tags(): MorphToMany
    {
        $relationship = TagIntegration::relationship();

        if (null === $relationship) {
            throw new LogicException('Install a tag provider to use tags.');
        }

        $tagTable = (new $relationship->model())->getTable();

        return $this->morphToMany(
            $relationship->model,
            $relationship->morphName,
            $relationship->table,
            $relationship->foreignPivotKey,
            $relationship->relatedPivotKey,
            $this->getKeyName(),
        )
            ->using($relationship->pivotModel)
            ->where("{$tagTable}.type", $this->tagType());
    }

    abstract protected function tagType(): string;
}
