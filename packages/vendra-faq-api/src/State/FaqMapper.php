<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

final class FaqMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): FaqResource
    {
        if ( ! $model instanceof Faq) {
            throw new UnexpectedValueException('Expected an FAQ model.');
        }

        $category = $model->faqCategory;

        if ( ! $category instanceof FaqCategory) {
            throw new UnexpectedValueException('An FAQ must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new FaqResource(
            id: $model->id,
            question: $this->normalizeTranslations($model->getTranslations('name')),
            answer: $this->normalizeTranslations($model->getTranslations('description')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            position: $model->position,
            active: $model->active,
            faqCategory: new ResourceReference(
                $category->id,
                'FaqCategory',
                is_string($categoryName) ? $categoryName : null,
            ),
            multimedia: $model->multimedia
                ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->values()
                ->all(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
