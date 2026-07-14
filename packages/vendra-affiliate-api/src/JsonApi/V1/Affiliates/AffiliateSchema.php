<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates;

use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\QueryBuilder\JsonApiBuilder;
use LaravelJsonApi\Eloquent\Schema;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;

/**
 * Public read-only schema for referral landing pages. It intentionally
 * exposes only the referral code and creation date: no user relation,
 * commission terms, or financial data, and suspended affiliates are
 * invisible on every query.
 */
final class AffiliateSchema extends Schema
{
    public static string $model = Affiliate::class;

    /**
     * @var array<string, int>|null
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * @param \Illuminate\Database\Eloquent\Builder<Affiliate>|null $query
     */
    public function newQuery($query = null): JsonApiBuilder
    {
        $query ??= $this->newInstance()->newQuery();

        $query->where('status', AffiliateStatusEnum::Active);

        return parent::newQuery($query);
    }

    /**
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('code')
                ->readOnly(),

            DateTime::make('created_at')
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
            WhereIdIn::make($this),

            Where::make('code'),
        ];
    }

    public function pagination(): PagePagination
    {
        return PagePagination::make();
    }
}
