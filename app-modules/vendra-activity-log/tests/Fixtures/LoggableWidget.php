<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;

/**
 * A model that opts into activity logging solely by implementing the marker
 * contract, without depending on the activity-log package.
 */
final class LoggableWidget extends Model implements ShouldLogActivity
{
    protected $table = 'activity_log_widgets';

    /** @var list<string> */
    protected $fillable = ['name', 'description'];
}
