<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1\Attributes;

use Illuminate\Database\Eloquent\Builder;
use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\QueryBuilder\JsonApiBuilder;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraAttribute\Models\Attribute;

final class AttributeSchema extends Schema
{
    public static string $model = Attribute::class;

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

            Str::make('name'),

            Str::make('description'),

            Str::make('unit'),

            Number::make('position')
                ->readOnly(),

            Boolean::make('status'),

            DateTime::make('created_at')
                ->readOnly(),

            DateTime::make('updated_at')
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
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }

    /**
     * The public read API must never serve inactive attributes.
     *
     * @param  Builder|null  $query
     */
    public function newQuery($query = null): JsonApiBuilder
    {
        $query ??= $this->newInstance()->newQuery();

        return parent::newQuery($query->where('status', true));
    }
}
