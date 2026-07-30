<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

final class CustomPageMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): CustomPageResource
    {
        if ( ! $model instanceof CustomPage) {
            throw new UnexpectedValueException('Expected a custom page model.');
        }

        $category = $model->customPageCategory;

        if ( ! $category instanceof CustomPageCategory) {
            throw new UnexpectedValueException('A custom page must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new CustomPageResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            position: $model->position,
            active: $model->active,
            customPageCategory: new ResourceReference(
                $category->id,
                'CustomPageCategory',
                is_string($categoryName) ? $categoryName : null,
            ),
            multimedia: $model->multimedia
                ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->values()
                ->all(),
        );
    }
}
