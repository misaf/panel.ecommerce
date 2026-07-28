<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraMultimedia\Models\Multimedia;
use Misaf\VendraMultimediaApi\ApiResource\Asset;

/**
 * @extends EloquentResourceProvider<Multimedia, Asset>
 */
final class AssetProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        return Multimedia::query()->select(['id', 'uuid', 'name', 'collection_name', 'mime_type', 'size']);
    }

    protected function toResource(Model $model, Operation $operation): Asset
    {
        /** @var Multimedia $model */
        return new Asset(
            id: $model->id,
            uuid: $model->uuid,
            name: $model->name,
            collection: $model->collection_name,
            mimeType: $model->mime_type,
            bytes: $model->size,
        );
    }
}
