<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Events;

/**
 * Fired when a recipient complains about an email (spam report)
 */
final class EmailComplainedEvent extends BaseEmailEvent {}
