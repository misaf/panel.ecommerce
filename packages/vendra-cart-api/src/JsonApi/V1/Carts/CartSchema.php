<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\Carts;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Has;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereDoesntHave;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraCart\Models\Cart;

final class CartSchema extends Schema
{
    public static string $model = Cart::class;

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

            Str::make('token')
                ->readOnly(),

            Str::make('owner_label')
                ->readOnly(),

            DateTime::make('expires_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            HasMany::make('items')
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

            Where::make('token')
                ->singular(),

            Where::make('expires-before', 'expires_at')
                ->lt(),

            Where::make('expires-after', 'expires_at')
                ->gt(),

            Has::make($this, 'items', 'has-items'),
            WhereHas::make($this, 'items', 'with-items'),
            WhereDoesntHave::make($this, 'items', 'without-items'),
        ];
    }

    /**
     * @return iterable<int, string>
     */
    public function includePaths(): iterable
    {
        return [
            'items',
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }
}
