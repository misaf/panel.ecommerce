<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageCategoryResource;
use UnexpectedValueException;

final class CustomPageCategoryMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): CustomPageCategoryResource
    {
        if ( ! $model instanceof CustomPageCategory) {
            throw new UnexpectedValueException('Expected a custom-page category model.');
        }

        return new CustomPageCategoryResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            customPages: $model->customPages
                ->map(function (CustomPage $page): ResourceReference {
                    $name = $page->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $page->id,
                        'CustomPage',
                        is_string($name) ? $name : null,
                    );
                })
                ->all(),
        );
    }
}
