<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

/**
 * Marker interface for models that should have their activity logged.
 *
 * Implementing this interface has no effect on its own. When the
 * `misaf/vendra-activity-log` package is installed, it registers global
 * model-event listeners that record activity for every model implementing
 * this contract. When the package is absent, the model simply is not logged,
 * so modules can opt into activity logging without depending on the package.
 *
 * By default every fillable attribute (except `id`) is logged. A model may
 * narrow this by declaring `activityLogExcept(): array<int, string>`, which
 * the logger will honour when present.
 */
interface ShouldLogActivity {}
