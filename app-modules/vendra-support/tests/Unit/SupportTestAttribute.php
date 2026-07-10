<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Illuminate\Database\Eloquent\Model;

final class SupportTestAttribute extends Model
{
    public $timestamps = false;

    protected $table = 'support_test_attributes';

    protected $guarded = [];
}
