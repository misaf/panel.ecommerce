<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Tests\Fixtures;

use Illuminate\Http\Request;
use Misaf\VendraLocalization\Contracts\LocaleResolver;

final readonly class TenantLocaleResolver implements LocaleResolver
{
    public function __construct(
        private ?string $locale = 'fa',
    ) {}

    public function resolve(Request $request): ?string
    {
        return $this->locale;
    }
}
