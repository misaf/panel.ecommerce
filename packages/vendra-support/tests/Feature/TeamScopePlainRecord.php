<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Tenancy\BelongsToTenant;

final class TeamScopePlainRecord extends Model
{
    use BelongsToTenant;

    public $timestamps = false;

    protected $table = 'team_scope_plain_records';

    protected $guarded = [];
}
