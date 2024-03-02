<?php

declare(strict_types=1);

namespace App\Models\Order;

use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyCategory;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

final class Order extends Model
{
    use HasFactory;

    use SoftDeletes;

    use TraitsBelongsToThrough;

    protected $casts = [
        'id'              => 'integer',
        'user_id'         => 'integer',
        'currency_id'     => 'integer',
        'description'     => 'string',
        'discount_amount' => 'integer',
        'reference_code'  => 'string',
        'status'          => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'currency_id',
        'description',
        'discount_amount',
        'reference_code',
        'status',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function currencyCategory(): BelongsToThrough
    {
        return $this->belongsToThrough(CurrencyCategory::class, Currency::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
