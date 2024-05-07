<?php

declare(strict_types=1);

namespace App\JsonApi\V1\ProductPrices;

use App\Models;
use LaravelJsonApi\Eloquent\Fields;
use LaravelJsonApi\Eloquent\Filters;
use LaravelJsonApi\Eloquent\Pagination;
use LaravelJsonApi\Eloquent\Schema;

final class ProductPriceSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Models\Product\ProductPrice::class;

    protected ?array $defaultPagination = ['number' => 1];

    public function fields(): array
    {
        return [
            Fields\ID::make(),

            Fields\ArrayHash::make('price'),

            Fields\DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            Fields\DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            Fields\Relations\BelongsTo::make('product')
                ->readOnly(),

            Fields\Relations\BelongsTo::make('currency')
                ->readOnly(),
        ];
    }

    public function filters(): array
    {
        return [
            Filters\WhereIdIn::make($this),

            Filters\WhereIdNotIn::make($this, 'exclude'),
        ];
    }

    public function includePaths(): iterable
    {
        return [
            'currency',
            'product',
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Pagination\PagePagination
     */
    public function pagination(): Pagination\PagePagination
    {
        return Pagination\PagePagination::make();
    }
}
