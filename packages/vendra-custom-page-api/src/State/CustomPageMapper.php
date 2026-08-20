<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageResource;
use Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia;

final class CustomPageMapper implements ResourceMapper
{
    use MapsPublicMultimedia;
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): CustomPageResource
    {
        $this->expectModel($model, CustomPage::class, 'Expected a custom page model.');
        $this->expectModel($category = $model->customPageCategory, CustomPageCategory::class, 'A custom page must belong to a category.');

        return new CustomPageResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            position: $model->position,
            active: $model->active,
            customPageCategory: $this->referenceTo($category, 'CustomPageCategory'),
            multimedia: $this->publicMultimedia($model),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
