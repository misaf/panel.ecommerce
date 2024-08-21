<?php

declare(strict_types=1);

namespace Termehsoft\Order\Models;

use App\Casts\DateCast;
use App\Models\BaseModel;
use Termehsoft\Currency;
use Termehsoft\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Termehsoft\Order\Observers\OrderObserver;
use Znck\Eloquent\Traits\BelongsToThrough as TraitBelongsToThrough;

#[ObservedBy([OrderObserver::class])]
final class Order extends BaseModel implements
    Currency\Contracts\BelongsToCurrency,
    User\Contracts\BelongsToUser
{
    use Currency\Traits\BelongsToCurrency;
    use SoftDeletes;
    use TraitBelongsToThrough;
    use User\Traits\BelongsToUser;

    protected $casts = [
        'id'              => 'integer',
        'user_id'         => 'integer',
        'currency_id'     => 'integer',
        'description'     => 'string',
        'discount_amount' => 'integer',
        'reference_code'  => 'string',
        'status'          => 'boolean',
        'created_at'      => DateCast::class,
        'updated_at'      => DateCast::class,
        'deleted_at'      => DateCast::class,
    ];

    protected $fillable = [
        'user_id',
        'currency_id',
        'description',
        'discount_amount',
        'reference_code',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'tenant_id',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
