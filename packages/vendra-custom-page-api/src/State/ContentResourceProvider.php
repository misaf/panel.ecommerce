<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageCategoryResource;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;

/**
 * @extends EloquentResourceProvider<Model, CustomPageResource|CustomPageCategoryResource>
 */
final class ContentResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (CustomPageCategoryResource::class === $operation->getClass()) {
            return CustomPageCategory::query()->with('customPages:id,custom_page_category_id,name')->where('active', true);
        }

        return CustomPage::query()->with(['customPageCategory:id,name', 'multimedia'])->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): CustomPageResource|CustomPageCategoryResource
    {
        if ($model instanceof CustomPageCategory) {
            return new CustomPageCategoryResource(
                id: $model->id,
                title: $model->getTranslations('name'),
                customPages: $model->customPages
                    ->map(fn(CustomPage $page): ResourceReference => new ResourceReference($page->id, 'CustomPage', $page->getTranslation('name', app()->getLocale())))
                    ->all(),
            );
        }

        /** @var CustomPage $model */
        return new CustomPageResource(
            id: $model->id,
            title: $model->getTranslations('name'),
            body: $model->getTranslations('description'),
            slugs: $model->getTranslations('slug'),
            customPageCategory: new ResourceReference($model->customPageCategory->id, 'CustomPageCategory', $model->customPageCategory->getTranslation('name', app()->getLocale())),
            multimedia: $model->multimedia
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->all(),
        );
    }
}
