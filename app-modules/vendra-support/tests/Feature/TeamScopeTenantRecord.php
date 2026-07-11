<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Traits\BelongsToTenant;

final class TeamScopeTenantRecord extends Model
{
    use BelongsToTenant;

    public $timestamps = false;

    protected $table = 'team_scope_tenant_records';

    protected $guarded = [];
}
