<?php

declare(strict_types=1);

namespace Misaf\VendraDeveloperLogins\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Resolves the host application's user model so the module can offer
 * developer logins without depending on a concrete user package.
 */
final class DeveloperLoginsUsers
{
    /**
     * @return class-string<Model>
     */
    public static function model(): string
    {
        /** @var class-string<Model> */
        return Config::string('auth.providers.users.model');
    }
}
