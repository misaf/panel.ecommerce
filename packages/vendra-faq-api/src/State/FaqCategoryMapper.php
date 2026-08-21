<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqCategoryResource;
use Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia;

final class FaqCategoryMapper implements ResourceMapper
{
    use MapsPublicMultimedia;
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): FaqCategoryResource
    {
        $this->expectModel($model, FaqCategory::class, 'Expected an FAQ category model.');

        return new FaqCategoryResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslationDocuments($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            faqs: $this->referencesTo($model->faqs, 'Faq'),
            multimedia: $this->publicMultimedia($model, onlyWhenLoaded: true),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
