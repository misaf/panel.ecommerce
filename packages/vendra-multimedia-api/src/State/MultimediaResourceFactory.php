<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Throwable;

final class MultimediaResourceFactory
{
    public static function make(Model $media): MultimediaResource
    {
        return new MultimediaResource(
            id: (int) $media->getKey(),
            uuid: (string) $media->getAttribute('uuid'),
            name: (string) $media->getAttribute('name'),
            fileName: (string) $media->getAttribute('file_name'),
            collection: (string) $media->getAttribute('collection_name'),
            mimeType: $media->getAttribute('mime_type'),
            bytes: (int) $media->getAttribute('size'),
            url: self::safeUrl($media),
            generatedConversions: $media->getAttribute('generated_conversions') ?? [],
        );
    }

    private static function safeUrl(Model $media): ?string
    {
        if ( ! method_exists($media, 'getUrl')) {
            return null;
        }

        try {
            return $media->getUrl();
        } catch (Throwable) {
            return null;
        }
    }
}
