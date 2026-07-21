<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Database\Factories\PlanFactory;
use Misaf\VendraSubscription\Enums\PeriodUnit;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $max_websites
 * @property PeriodUnit $period_unit
 * @property int $period_count
 * @property int $grace_days
 * @property int $price
 * @property string|null $currency_code
 * @property int $trial_days
 * @property list<string>|null $features
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'slug', 'description', 'max_websites', 'period_unit', 'period_count', 'grace_days', 'price', 'currency_code', 'trial_days', 'features', 'status'])]
#[UseFactory(PlanFactory::class)]
final class Plan extends Model implements ShouldLogActivity
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    use HasSlug;
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'name'          => 'string',
            'slug'          => 'string',
            'description'   => 'string',
            'max_websites'  => 'integer',
            'period_unit'   => PeriodUnit::class,
            'period_count'  => 'integer',
            'grace_days'    => 'integer',
            'price'         => 'integer',
            'currency_code' => 'string',
            'trial_days'    => 'integer',
            'features'      => 'array',
            'status'        => 'boolean',
        ];
    }

    /**
     * @param  Builder<Plan>  $query
     * @return Builder<Plan>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * @param  Builder<Plan>  $query
     * @return Builder<Plan>
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Resolve the subscription end date for a period starting at the given date.
     */
    public function resolveEndDate(Carbon $start): Carbon
    {
        return Carbon::instance($this->period_unit->advance($start, $this->period_count));
    }

    /**
     * Whether this plan has no recurring charge.
     */
    public function isFree(): bool
    {
        return 0 === $this->price;
    }

    /**
     * Whether the plan offers a trial period.
     */
    public function hasTrial(): bool
    {
        return $this->trial_days > 0;
    }

    /**
     * Whether the plan grants the given feature entitlement.
     */
    public function allows(string $feature): bool
    {
        return in_array($feature, $this->features ?? [], true);
    }

    /**
     * The moment a lapsed subscription's websites should be suspended:
     * the period end plus the plan's grace window.
     */
    public function resolveSuspendDate(Carbon $endsAt): Carbon
    {
        return $endsAt->copy()->addDays($this->grace_days);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
