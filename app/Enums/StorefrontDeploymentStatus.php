<?php

declare(strict_types=1);

namespace App\Enums;

enum StorefrontDeploymentStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Requested = 'requested';
    case Ready = 'ready';
    case Failed = 'failed';
}
