<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use DutchCodingCompany\FilamentSocialite\Models\SocialiteUser as DutchCodingCompanySocialiteUser;
use Misaf\Tenant\Traits\BelongsToTenant;

final class SocialiteUser extends DutchCodingCompanySocialiteUser
{
    use BelongsToTenant;
}
