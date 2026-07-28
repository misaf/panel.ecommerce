<?php

declare(strict_types=1);

namespace App\Support\Context;

/**
 * Host-owned observability context keys passed through
 * {@see \Misaf\VendraSupport\Context\RequestJobContext::$metadata}. Reseller and
 * Filament panels are host concepts, so their keys live here rather than in the
 * domain-agnostic support layer.
 */
final class AppContextKeys
{
    public const string PANEL_ID = 'panel_id';

    public const string RESELLER_ID = 'reseller_id';
}
