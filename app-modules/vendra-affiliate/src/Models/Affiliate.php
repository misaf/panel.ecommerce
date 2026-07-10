<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraAffiliate\Facades\AffiliateService;
use Misaf\VendraAffiliate\Traits\HasAffiliateUser;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraSupport\Traits\BelongsToTenant;
use Misaf\VendraUser\Traits\BelongsToUser;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property int $commission_percent
 * @property bool $is_processing
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['user_id', 'name', 'description', 'slug', 'commission_percent', 'is_processing', 'status'])]
#[Hidden(['tenant_id'])]
#[UseFactory(AffiliateFactory::class)]
final class Affiliate extends Model implements ShouldLogActivity
{
    use BelongsToTenant;
    use BelongsToUser;
    use HasAffiliateUser;

    /** @use HasFactory<AffiliateFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'                 => 'integer',
            'tenant_id'          => 'integer',
            'user_id'            => 'integer',
            'name'               => 'string',
            'description'        => 'string',
            'slug'               => 'string',
            'commission_percent' => 'integer',
            'is_processing'      => 'boolean',
            'status'             => 'boolean',
        ];
    }

    /**
     * @var list<string>
     */

    /**
     * @var list<string>
     */

    /**
     * @return void
     */
    protected static function booted(): void
    {
        self::creating(function (self $affiliate): void {
            $name = AffiliateService::generateName();

            $affiliate->name = $name;
            $affiliate->slug = Str::slug($name);
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
