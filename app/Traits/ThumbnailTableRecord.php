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

        $this->addMediaConversion('small')
            ->width(300);

        $this->addMediaConversion('medium')
            ->width(500);

        $this->addMediaConversion('large')
            ->width(800);

        $this->addMediaConversion('extra-large')
            ->width(1200);
    }
}
