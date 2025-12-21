<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

final class ModelLinkColumn extends TextColumn
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->url(function ($record) {
            if (null === $record) {
                return null;
            }

            $selectedResource = null;
            $relationship = Str::before($this->getName(), '.');
            $relatedRecord = $record->{$relationship};

            if (null === $relatedRecord) {
                return null;
            }

            $selectedResource = collect(Filament::getResources())
                ->first(fn($resource) => $relatedRecord instanceof ($resource::getModel()));

            return $selectedResource::getUrl('view', [
                'record' => $relatedRecord->getKey(),
            ]);
        });
    }
}
