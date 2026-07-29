<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraMultimedia\Models\Multimedia;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

/**
 * @extends EloquentResourceProvider<Multimedia, MultimediaResource>
 */
final class MultimediaResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return Multimedia::query()->select([
            'id', 'uuid', 'name', 'file_name', 'collection_name', 'mime_type', 'size',
            'disk', 'conversions_disk', 'manipulations', 'custom_properties', 'generated_conversions', 'responsive_images',
        ]);
    }

    protected function toResource(Model $model, Operation $operation): MultimediaResource
    {
        /** @var Multimedia $model */
        return MultimediaResourceFactory::make($model);
    }
}
