<?php

declare(strict_types=1);

namespace App\Console\Commands\ConvertData\Interfaces;

interface DataConverter
{
    public function migrate(): void;
}
