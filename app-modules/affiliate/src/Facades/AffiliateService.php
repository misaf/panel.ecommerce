<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generateName()
 * @method static string generateSlug()
 *
 * @see AffiliateService
 */
final class AffiliateService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'affiliate-service';
    }
}
