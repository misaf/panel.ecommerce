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
use Misaf\VendraCustomPageApi\ApiResource\ContentPage;
use Misaf\VendraCustomPageApi\ApiResource\ContentSection;

/**
 * @extends EloquentResourceProvider<Model, ContentPage|ContentSection>
 */
final class ContentResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (ContentSection::class === $operation->getClass()) {
            return CustomPageCategory::query()->with('customPages:id,custom_page_category_id,name')->where('active', true);
        }

        return CustomPage::query()->with(['customPageCategory:id,name', 'multimedia'])->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): ContentPage|ContentSection
    {
        if ($model instanceof CustomPageCategory) {
            return new ContentSection(
                id: $model->id,
                title: $model->getTranslations('name'),
                pages: $model->customPages
                    ->map(fn(CustomPage $page): ResourceReference => new ResourceReference($page->id, 'CustomPage', $page->getTranslation('name', app()->getLocale())))
                    ->all(),
            );
        }

        /** @var CustomPage $model */
        return new ContentPage(
            id: $model->id,
            title: $model->getTranslations('name'),
            body: $model->getTranslations('description'),
            slugs: $model->getTranslations('slug'),
            section: new ResourceReference($model->customPageCategory->id, 'CustomPageCategory', $model->customPageCategory->getTranslation('name', app()->getLocale())),
            multimedia: $model->multimedia
                ->map(fn(Model $media): ResourceReference => new ResourceReference($media->getKey(), 'Multimedia', $media->getAttribute('name')))
                ->all(),
        );
    }
}
