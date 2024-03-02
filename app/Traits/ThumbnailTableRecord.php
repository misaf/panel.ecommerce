<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ThumbnailTableRecord
{
    public function registerMediaCollections(): void {}

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb-table')
            ->width(48);
    }
}
