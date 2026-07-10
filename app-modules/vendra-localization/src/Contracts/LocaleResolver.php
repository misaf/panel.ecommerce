<?php

declare(strict_types=1);

namespace Misaf\VendraLocalization\Contracts;

use Illuminate\Http\Request;

interface LocaleResolver
{
    /**
     * Resolve the preferred locale for the current request, or return
     * null when this resolver's source does not provide one.
     */
    public function resolve(Request $request): ?string;
}
