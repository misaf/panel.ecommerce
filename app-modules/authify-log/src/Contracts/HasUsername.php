<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Contracts;

interface HasUsername
{
    public function getAuthifyLogUsername(): string;
}
