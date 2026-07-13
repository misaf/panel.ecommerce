<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Traits\BelongsToTenant;

abstract class TenantModel extends Model
{
    use BelongsToTenant;
}
