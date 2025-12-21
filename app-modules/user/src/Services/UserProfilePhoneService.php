<?php

declare(strict_types=1);

namespace Misaf\User\Services;

use Propaganistas\LaravelPhone\PhoneNumber;

final class UserProfilePhoneService
{
    /**
     * @param string $country
     * @param PhoneNumber $phone
     * @return string
     */
    public static function phoneE164(string $country, PhoneNumber $phone): string
    {
        return phone($phone->__toString(), $country)->formatE164();
    }

    /**
     * @param string $country
     * @param PhoneNumber $phone
     * @return string|null
     */
    public static function phoneNational(string $country, PhoneNumber $phone): ?string
    {
        return preg_replace('/[^0-9]/', '', phone($phone->__toString(), $country)->formatNational());
    }

    /**
     * @param PhoneNumber $phone
     * @return string|null
     */
    public static function phoneNormalized(PhoneNumber $phone): ?string
    {
        return preg_replace('/[^0-9]/', '', $phone->__toString());
    }
}
