<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqCategoryResource;
use Misaf\VendraFaqApi\ApiResource\FaqResource;

/**
 * @extends EloquentResourceProvider<Model, FaqResource|FaqCategoryResource>
 */
final class HelpResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (FaqCategoryResource::class === $operation->getClass()) {
            return FaqCategory::query()->with('faqs:id,faq_category_id,name')->where('active', true);
        }

        return Faq::query()->with(['faqCategory:id,name', 'multimedia'])->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): FaqResource|FaqCategoryResource
    {
        if ($model instanceof FaqCategory) {
            return new FaqCategoryResource(
                id: $model->id,
                title: $model->getTranslations('name'),
                faqs: $model->faqs
                    ->map(fn(Faq $faq): ResourceReference => new ResourceReference($faq->id, 'Faq', $faq->getTranslation('name', app()->getLocale())))
                    ->all(),
            );
        }

        /** @var Faq $model */
        return new FaqResource(
            id: $model->id,
            question: $model->getTranslations('name'),
            answer: $model->getTranslations('description'),
            topic: new ResourceReference($model->faqCategory->id, 'FaqCategory', $model->faqCategory->getTranslation('name', app()->getLocale())),
            multimedia: $model->multimedia
                ->map(fn(Model $media): ResourceReference => new ResourceReference($media->getKey(), 'Multimedia', $media->getAttribute('name')))
                ->all(),
        );
    }
}
