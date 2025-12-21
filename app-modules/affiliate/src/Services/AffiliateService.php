<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Services;

final class AffiliateService
{
    private const NAME_CHARACTERS = '123456789';

    private const NAME_LENGTH = 20;

    private const SLUG_CHARACTERS = '123456789';

    private const SLUG_LENGTH = 20;

    public function generateName(): string
    {
        return mb_substr(str_shuffle(str_repeat(self::NAME_CHARACTERS, self::NAME_LENGTH)), 0, self::NAME_LENGTH);
    }

    public function generateSlug(): string
    {
        return mb_substr(str_shuffle(str_repeat(self::SLUG_CHARACTERS, self::SLUG_LENGTH)), 0, self::SLUG_LENGTH);
    }
}
