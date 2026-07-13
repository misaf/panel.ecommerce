<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Illuminate\Database\Eloquent\Model;

final class SupportTestCurrency extends Model
{
    public $timestamps = false;

    protected $table = 'support_test_currencies';

    protected $guarded = [];
}
