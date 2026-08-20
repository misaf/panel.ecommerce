<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqResource;
use Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia;

final class FaqMapper implements ResourceMapper
{
    use MapsPublicMultimedia;
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): FaqResource
    {
        $this->expectModel($model, Faq::class, 'Expected an FAQ model.');
        $this->expectModel($category = $model->faqCategory, FaqCategory::class, 'An FAQ must belong to a category.');

        return new FaqResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            position: $model->position,
            active: $model->active,
            faqCategory: $this->referenceTo($category, 'FaqCategory'),
            multimedia: $this->publicMultimedia($model),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
