<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Events;

/**
 * Fired when an email bounces (hard or soft bounce)
 */
final class EmailBouncedEvent extends BaseEmailEvent {}
