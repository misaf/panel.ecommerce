<?php

declare(strict_types=1);

namespace Misaf\VendraDocument\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Misaf\VendraDocument\Database\Factories\DocumentFactory;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraSupport\Traits\BelongsToTenant;
use Misaf\VendraUserProfile\Models\UserProfile;

#[Fillable([
    'user_profile_id',
    'type',
    'issuing_country_code',
    'number',
    'disk',
    'path',
    'issued_at',
    'expires_at',
    'verified_at',
    'metadata',
    'notes',
])]
#[Hidden(['tenant_id'])]
#[UseFactory(DocumentFactory::class)]
final class Document extends Model implements ShouldLogActivity
{
    use BelongsToTenant;

    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $attributes = [
        'disk' => 'local',
    ];

    /** @return BelongsTo<UserProfile, $this> */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id'                   => 'integer',
            'tenant_id'            => 'integer',
            'user_profile_id'      => 'integer',
            'type'                 => 'string',
            'issuing_country_code' => 'string',
            'number'               => 'string',
            'disk'                 => 'string',
            'path'                 => 'string',
            'issued_at'            => 'date',
            'expires_at'           => 'date',
            'verified_at'          => 'datetime',
            'metadata'             => 'array',
            'notes'                => 'string',
        ];
    }
}
