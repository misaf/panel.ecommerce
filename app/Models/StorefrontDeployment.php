<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StorefrontDeploymentStatus;
use Database\Factories\StorefrontDeploymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Misaf\VendraTenant\Models\Tenant;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $slug
 * @property string $domain
 * @property string $theme
 * @property array<string, mixed> $configuration
 * @property StorefrontDeploymentStatus $status
 * @property string|null $provider_reference
 * @property string|null $image_digest
 * @property Carbon|null $requested_at
 * @property Carbon|null $deployed_at
 * @property Carbon|null $failed_at
 * @property string|null $error
 */
#[Fillable([
    'tenant_id', 'slug', 'domain', 'theme', 'configuration', 'status',
    'provider_reference', 'image_digest', 'requested_at', 'deployed_at',
    'failed_at', 'error',
])]
#[UseFactory(StorefrontDeploymentFactory::class)]
final class StorefrontDeployment extends Model
{
    /** @use HasFactory<StorefrontDeploymentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tenant_id'     => 'integer',
            'configuration' => 'array',
            'status'        => StorefrontDeploymentStatus::class,
            'requested_at'  => 'datetime',
            'deployed_at'   => 'datetime',
            'failed_at'     => 'datetime',
        ];
    }
}
