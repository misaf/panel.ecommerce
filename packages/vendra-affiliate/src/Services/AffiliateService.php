<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Services;

final class AffiliateService
{
    private const NAME_CHARACTERS = '123456789';

    private const NAME_LENGTH = 20;

    private const SLUG_CHARACTERS = '123456789';

    private const SLUG_LENGTH = 20;

    public function generateName(): string
    {
        return $this->randomString(self::NAME_CHARACTERS, self::NAME_LENGTH);
    }

    public function generateSlug(): string
    {
        return $this->randomString(self::SLUG_CHARACTERS, self::SLUG_LENGTH);
    }

    private function randomString(string $characters, int $length): string
    {
        $maxIndex = mb_strlen($characters) - 1;
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $maxIndex)];
        }

        return $result;
    }
}
