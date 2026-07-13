<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraAttribute\Models\AttributeValue;

final class AttributeValueSchema extends Schema
{
    public static string $model = AttributeValue::class;

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

            Number::make('attribute_id'),

            Str::make('value'),

            Number::make('position')
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
}
