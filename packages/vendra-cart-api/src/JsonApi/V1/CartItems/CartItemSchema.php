<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\CartItems;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereDoesntHave;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraCart\Models\CartItem;

final class CartItemSchema extends Schema
{
    public static string $model = CartItem::class;

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

            Str::make('sellable_type')
                ->readOnly(),

            Number::make('sellable_id')
                ->sortable()
                ->readOnly(),

            Number::make('quantity')
                ->sortable()
                ->readOnly(),

            ArrayHash::make('metadata')
                ->readOnly(),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            BelongsTo::make('cart')
                ->readOnly(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            WhereIdNotIn::make($this, 'exclude'),

            Where::make('cart', 'cart_id')
                ->asInteger(),

            Where::make('sellable-type', 'sellable_type'),

            Where::make('sellable-id', 'sellable_id')
                ->asInteger(),

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

            WhereHas::make($this, 'cart', 'with-cart'),
            WhereDoesntHave::make($this, 'cart', 'without-cart'),
        ];
    }

    /**
     * @return iterable<int, string>
     */
    public function includePaths(): iterable
    {
        return [
            'cart',
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }
}
