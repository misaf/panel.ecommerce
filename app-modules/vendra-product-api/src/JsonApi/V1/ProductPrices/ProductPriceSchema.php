<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\ProductPrices;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\OnlyTrashed;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereDoesntHave;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Filters\WithTrashed;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraProduct\Models\ProductPrice;

final class ProductPriceSchema extends Schema
{
    public static string $model = ProductPrice::class;

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

            Str::make('currency_code'),

            Number::make('price')
                ->sortable(),

            ArrayHash::make('money', 'price')
                ->readOnly(),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            BelongsTo::make('product')
                ->readOnly(),

            BelongsTo::make('productCategory')
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
            ...$this->getAttributeFilters(),
            ...$this->getRelationFilters(),
            ...$this->getSoftDeleteFilters(),
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

    /**
     * @return array<int, Filter>
     */
    private function getAttributeFilters(): array
    {
        return [
            Where::make('currency_code'),

            Where::make('price')
                ->asInteger(),

            Where::make('gt-price', 'price')
                ->asInteger()
                ->gt(),

            Where::make('gte-price', 'price')
                ->asInteger()
                ->gte(),

            Where::make('lt-price', 'price')
                ->asInteger()
                ->lt(),

            Where::make('lte-price', 'price')
                ->asInteger()
                ->lte(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getRelationFilters(): array
    {
        return [
            WhereHas::make($this, 'product', 'with-product'),
            WhereDoesntHave::make($this, 'product', 'without-product'),

            WhereHas::make($this, 'productCategory', 'with-product-category'),
            WhereDoesntHave::make($this, 'productCategory', 'without-product-category'),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getSoftDeleteFilters(): array
    {
        return [
            WithTrashed::make('with-trashed'),
            OnlyTrashed::make('only-trashed'),
        ];
    }

    /**
     * @return iterable<int, string>
     */
    public function includePaths(): iterable
    {
        return [
            'product',
            'productCategory',
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }
}
