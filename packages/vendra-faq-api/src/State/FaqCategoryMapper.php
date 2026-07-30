<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqCategoryResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

final class FaqCategoryMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): FaqCategoryResource
    {
        if ( ! $model instanceof FaqCategory) {
            throw new UnexpectedValueException('Expected an FAQ category model.');
        }

        return new FaqCategoryResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            faqs: $model->faqs
                ->map(function (Faq $faq): ResourceReference {
                    $name = $faq->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $faq->id,
                        'Faq',
                        is_string($name) ? $name : null,
                    );
                })
                ->all(),
            multimedia: $model->relationLoaded('multimedia')
                ? $model->multimedia
                    ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->values()
                    ->all()
                : [],
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
