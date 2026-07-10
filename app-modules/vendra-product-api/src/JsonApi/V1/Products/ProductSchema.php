<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\Products;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Has;
use LaravelJsonApi\Eloquent\Filters\OnlyTrashed;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereDoesntHave;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Filters\WithTrashed;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraApi\JsonApi\Sorting\RandomPositionSort;
use Misaf\VendraProduct\Models\Product;

final class ProductSchema extends Schema
{
    public static string $model = Product::class;

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

            ArrayHash::make('name'),

            ArrayHash::make('description'),

            ArrayHash::make('slug'),

            Str::make('token')
                ->readOnly(),

            Number::make('quantity')
                ->sortable(),

            Number::make('stock_threshold')
                ->sortable(),

            Boolean::make('in_stock')
                ->sortable(),

            Number::make('position')
                ->sortable()
                ->readOnly(),

            Boolean::make('available_soon')
                ->sortable(),

            DateTime::make('availability_date'),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            BelongsTo::make('productCategory')
                ->readOnly(),

            HasMany::make('productPrices')
                ->readOnly(),

            HasOne::make('latestProductPrice')
                ->type('product-prices')
                ->readOnly(),

            HasOne::make('oldestProductPrice')
                ->type('product-prices')
                ->readOnly(),

            BelongsToMany::make('multimedia')
                ->readOnly(),

            ...$this->getAttributeValueFields(),
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
        $locale = app()->getLocale();

        return [
            Where::make('name', "name->{$locale}")
                ->using('like')
                ->deserializeUsing(fn(string $value): string => "%{$value}%"),

            Where::make('slug', "slug->{$locale}")
                ->using('like')
                ->deserializeUsing(fn(string $value): string => "%{$value}%"),

            Where::make('token')
                ->singular(),

            Where::make('quantity')
                ->asInteger(),

            Where::make('gt-quantity', 'quantity')
                ->asInteger()
                ->gt(),

            Where::make('gte-quantity', 'quantity')
                ->asInteger()
                ->gte(),

            Where::make('lt-quantity', 'quantity')
                ->asInteger()
                ->lt(),

            Where::make('lte-quantity', 'quantity')
                ->asInteger()
                ->lte(),

            Where::make('stock_threshold')
                ->asInteger(),

            Where::make('gt-stock-threshold', 'stock_threshold')
                ->asInteger()
                ->gt(),

            Where::make('gte-stock-threshold', 'stock_threshold')
                ->asInteger()
                ->gte(),

            Where::make('lt-stock-threshold', 'stock_threshold')
                ->asInteger()
                ->lt(),

            Where::make('lte-stock-threshold', 'stock_threshold')
                ->asInteger()
                ->lte(),

            Where::make('in_stock')
                ->asBoolean(),

            Where::make('available_soon')
                ->asBoolean(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getRelationFilters(): array
    {
        return [
            WhereHas::make($this, 'productCategory', 'with-product-category'),
            WhereDoesntHave::make($this, 'productCategory', 'without-product-category'),

            Has::make($this, 'productPrices', 'has-product-prices'),
            WhereHas::make($this, 'productPrices', 'with-product-prices'),
            WhereDoesntHave::make($this, 'productPrices', 'without-product-prices'),

            WhereHas::make($this, 'latestProductPrice', 'with-latest-product-price'),
            WhereDoesntHave::make($this, 'latestProductPrice', 'without-latest-product-price'),

            WhereHas::make($this, 'oldestProductPrice', 'with-oldest-product-price'),
            WhereDoesntHave::make($this, 'oldestProductPrice', 'without-oldest-product-price'),

            Has::make($this, 'multimedia', 'has-multimedia'),
            WhereHas::make($this, 'multimedia', 'with-multimedia'),
            WhereDoesntHave::make($this, 'multimedia', 'without-multimedia'),

            ...$this->getAttributeValueFilters(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getAttributeValueFilters(): array
    {
        if ( ! class_exists('Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema')) {
            return [];
        }

        return [
            Has::make($this, 'attributeValues', 'has-attribute-values'),
            WhereHas::make($this, 'attributeValues', 'with-attribute-values'),
            WhereDoesntHave::make($this, 'attributeValues', 'without-attribute-values'),
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
     * @return array<int, Field>
     */
    private function getAttributeValueFields(): array
    {
        if ( ! class_exists('Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema')) {
            return [];
        }

        return [
            HasMany::make('attributeValues')
                ->readOnly(),
        ];
    }

    /**
     * @return iterable<int, string>
     */
    public function includePaths(): iterable
    {
        $paths = [
            'productCategory',
            'productPrices',
            'latestProductPrice',
            'oldestProductPrice',
            'multimedia',
        ];

        if (class_exists('Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema')) {
            $paths[] = 'attributeValues';
        }

        return $paths;
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
