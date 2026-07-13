<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Illuminate\Support\Facades\Config;

final class SandboxMode
{
    public static function enabled(): bool
    {
        return Config::boolean('vendra-support.sandbox', false);
    }
}
