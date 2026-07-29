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
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;

/**
 * @extends EloquentResourceProvider<Model, FaqResource|FaqCategoryResource>
 */
final class HelpResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (FaqCategoryResource::class === $operation->getClass()) {
            return FaqCategory::query()
                ->with([
                    'faqs:id,faq_category_id,name',
                    'multimedia',
                ])
                ->where('active', true);
        }

        $query = Faq::query()
            ->with([
                'faqCategory:id,name,slug,description,position,active,created_at,updated_at',
                'multimedia',
            ])
            ->where('active', true);

        return $query;
    }

    protected function toResource(Model $model, Operation $operation): FaqResource|FaqCategoryResource
    {
        if ($model instanceof FaqCategory) {
            return $this->toCategoryResource($model);
        }

        /** @var Faq $model */
        return new FaqResource(
            id: $model->id,
            question: $model->getTranslations('name'),
            answer: $model->getTranslations('description'),
            slugs: $model->getTranslations('slug'),
            position: $model->position,
            active: $model->active,
            topic: $this->toCategoryResource($model->faqCategory, includeFaqs: false),
            multimedia: $model->multimedia
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->all(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }

    private function toCategoryResource(FaqCategory $category, bool $includeFaqs = true): FaqCategoryResource
    {
        return new FaqCategoryResource(
            id: $category->id,
            title: $category->getTranslations('name'),
            slugs: $category->getTranslations('slug'),
            description: $category->getTranslations('description'),
            position: $category->position,
            active: $category->active,
            faqs: $includeFaqs
                ? $category->faqs
                    ->map(fn(Faq $faq): ResourceReference => new ResourceReference($faq->id, 'Faq', $faq->getTranslation('name', app()->getLocale())))
                    ->all()
                : [],
            multimedia: $category->relationLoaded('multimedia')
                ? $category->multimedia
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->all()
                : [],
            createdAt: $category->created_at->toAtomString(),
            updatedAt: $category->updated_at->toAtomString(),
        );
    }
}
