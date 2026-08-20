<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Context;

/**
 * Well-known observability keys passed through {@see RequestJobContext::$metadata}.
 *
 * They live beside the context itself rather than in the packages that set them
 * because a log line is only searchable if everybody writing it agrees on the
 * spelling: the panel middleware, the store provisioning job, and the reseller
 * panel all stamp the same two keys, and they sit in three different packages
 * with no dependency between them.
 *
 * These are labels, not domain concepts — nothing here resolves a reseller or
 * knows what a panel is.
 */
final class ContextKeys
{
    public const string PANEL_ID = 'panel_id';

    public const string RESELLER_ID = 'reseller_id';
}
