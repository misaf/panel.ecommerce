<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * A model that does NOT implement the marker contract and therefore must never
 * be logged, even while activity logging is enabled.
 */
final class PlainWidget extends Model
{
    protected $table = 'activity_log_widgets';

    /** @var list<string> */
    protected $fillable = ['name', 'description'];
}
