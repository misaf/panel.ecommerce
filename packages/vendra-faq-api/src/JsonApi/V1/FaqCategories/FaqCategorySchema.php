<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\V1\FaqCategories;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
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
use Misaf\VendraFaq\Models\FaqCategory;

final class FaqCategorySchema extends Schema
{
    public static string $model = FaqCategory::class;

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

            Number::make('position')
                ->sortable()
                ->readOnly(),

            Boolean::make('active')
                ->sortable(),

            DateTime::make('created_at')
                ->sortable()
                ->readOnly(),

            DateTime::make('updated_at')
                ->sortable()
                ->readOnly(),

            HasMany::make('faqs')
                ->readOnly(),

            BelongsToMany::make('multimedia')
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
    private function getSoftDeleteFilters(): array
    {
        return [
            WithTrashed::make('with-trashed'),
            OnlyTrashed::make('only-trashed'),
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

            Where::make('active')
                ->asBoolean(),
        ];
    }

    /**
     * @return array<int, Filter>
     */
    private function getRelationFilters(): array
    {
        return [
            Has::make($this, 'faqs', 'has-faqs'),
            WhereHas::make($this, 'faqs', 'with-faqs'),
            WhereDoesntHave::make($this, 'faqs', 'without-faqs'),

            Has::make($this, 'multimedia', 'has-multimedia'),
            WhereHas::make($this, 'multimedia', 'with-multimedia'),
            WhereDoesntHave::make($this, 'multimedia', 'without-multimedia'),
        ];
    }

    /**
     * @return iterable<int, string>
     */
    public function includePaths(): iterable
    {
        return [
            'faqs',
            'multimedia',
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
