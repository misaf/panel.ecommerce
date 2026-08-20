<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageCategoryResource;

final class CustomPageCategoryMapper implements ResourceMapper
{
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): CustomPageCategoryResource
    {
        $this->expectModel($model, CustomPageCategory::class, 'Expected a custom-page category model.');

        return new CustomPageCategoryResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            customPages: $this->referencesTo($model->customPages, 'CustomPage'),
        );
    }
}
