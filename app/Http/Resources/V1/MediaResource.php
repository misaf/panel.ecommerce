<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type'       => 'media',
            'id'         => $this->id,
            'attributes' => [
                'collection_name'       => $this->collection_name,
                'file_name'             => $this->file_name,
                'mime_type'             => $this->mime_type,
                'size'                  => $this->size,
                'custom_properties'     => $this->custom_properties,
                'generated_conversions' => $this->generated_conversions,
                'responsive_images'     => $this->responsive_images,
                'order_column'          => $this->order_column,
                'created_at'            => $this->created_at,
                'updated_at'            => $this->updated_at,
            ],
        ];
    }
}
