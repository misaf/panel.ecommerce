<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Feature;

use Illuminate\Foundation\Auth\User;

final class TeamScopeUser extends User
{
    public $timestamps = false;

    protected $table = 'team_scope_users';

    protected $guarded = [];
}
