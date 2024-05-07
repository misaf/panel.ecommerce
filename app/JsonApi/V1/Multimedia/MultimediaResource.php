<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Multimedia;

use LaravelJsonApi\Core\Resources\JsonApiResource;

final class MultimediaResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        return [
            'uuid'                   => $this->uuid,
            'collection_name'        => $this->collection_name,
            'name'                   => $this->name,
            'file_name'              => $this->file_name,
            'mime_type'              => $this->mime_type,
            'disk'                   => $this->disk,
            'conversions_disk'       => $this->conversions_disk,
            'size'                   => $this->size,
            'manipulations'          => $this->manipulations,
            'custom_properties'      => $this->custom_properties,
            'generated_conversions'  => $this->generated_conversions,
            'responsive_images'      => $this->responsive_images,
            'order_column'           => $this->order_column,
            'created_at'             => $this->resource->created_at,
            'updated_at'             => $this->resource->updated_at,
        ];
    }
}
