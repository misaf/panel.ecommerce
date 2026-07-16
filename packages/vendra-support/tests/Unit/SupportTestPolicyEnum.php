<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

enum SupportTestPolicyEnum: string
{
    case Delete = 'delete-support-test';
    case DeleteAny = 'delete-any-support-test';
    case View = 'view-support-test';
    case ViewAny = 'view-any-support-test';
}
