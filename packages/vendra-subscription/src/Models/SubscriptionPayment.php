<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Database\Factories\SubscriptionPaymentFactory;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;

/**
 * @property int $id
 * @property int $subscription_id
 * @property string $payer_type
 * @property int $payer_id
 * @property string $provider
 * @property string $idempotency_key
 * @property string|null $provider_reference
 * @property int $amount
 * @property string $currency_code
 * @property SubscriptionPaymentStatus $status
 * @property int $attempt_count
 * @property string|null $failure_code
 * @property string|null $failure_message
 * @property array<string, mixed>|null $metadata
 * @property Carbon|null $processing_at
 * @property Carbon|null $paid_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $next_retry_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['subscription_id', 'payer_type', 'payer_id', 'provider', 'idempotency_key', 'provider_reference', 'amount', 'currency_code', 'status', 'attempt_count', 'failure_code', 'failure_message', 'metadata', 'processing_at', 'paid_at', 'failed_at', 'next_retry_at'])]
#[UseFactory(SubscriptionPaymentFactory::class)]
final class SubscriptionPayment extends Model implements ShouldLogActivity
{
    /** @use HasFactory<SubscriptionPaymentFactory> */
    use HasFactory;

    protected $attributes = [
        'status'        => SubscriptionPaymentStatus::Pending->value,
        'attempt_count' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subscription_id' => 'integer',
            'payer_id'        => 'integer',
            'amount'          => 'integer',
            'status'          => SubscriptionPaymentStatus::class,
            'attempt_count'   => 'integer',
            'metadata'        => 'array',
            'processing_at'   => 'datetime',
            'paid_at'         => 'datetime',
            'failed_at'       => 'datetime',
            'next_retry_at'   => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function payer(): MorphTo
    {
        return $this->morphTo();
    }
}
