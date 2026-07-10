<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAttribute\Concerns\HasAttributeValues;

final class AttributableRecord extends Model
{
    use HasAttributeValues;

    protected $table = 'attributable_records';

    protected $guarded = [];
}
