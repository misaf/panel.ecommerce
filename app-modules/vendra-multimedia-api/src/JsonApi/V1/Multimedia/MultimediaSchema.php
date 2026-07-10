<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\JsonApi\V1\Multimedia;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraApi\JsonApi\Sorting\RandomPositionSort;
use Misaf\VendraMultimedia\Models\Multimedia;

final class MultimediaSchema extends Schema
{
    public static string $model = Multimedia::class;

    /**
     * @var array<string, int>|null
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('uuid'),

            Str::make('collection_name'),

            Str::make('name'),

            Str::make('file_name'),

            Str::make('mime_type'),

            Str::make('disk'),

            Str::make('conversions_disk'),

            Number::make('size'),

            Str::make('manipulations'),

            Str::make('custom_properties'),

            Str::make('generated_conversions'),

            Str::make('responsive_images'),

            Number::make('order_column')
                ->sortable()
                ->readOnly(),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            ...$this->getPrimaryKeyFilters(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getPrimaryKeyFilters(): array
    {
        return [
            WhereIdIn::make($this),
            WhereIdNotIn::make($this, 'exclude'),
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }

    /**
     * @return iterable<int, Sortable>
     */
    public function sortables(): iterable
    {
        return [
            RandomPositionSort::make('random-position'),
        ];
    }
}
