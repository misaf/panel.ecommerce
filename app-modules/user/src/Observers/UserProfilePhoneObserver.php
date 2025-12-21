<?php

declare(strict_types=1);

namespace Misaf\User\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserProfilePhoneObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
}
