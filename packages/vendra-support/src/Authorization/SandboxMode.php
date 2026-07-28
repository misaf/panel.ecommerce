<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Authorization;

use Illuminate\Support\Facades\Config;

final class SandboxMode
{
    public static function enabled(): bool
    {
        return Config::boolean('vendra-support.sandbox', false);
    }
}
