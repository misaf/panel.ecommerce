<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Tests\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
final class TestTenant extends Model
{
    protected $table = 'tenants';
}
