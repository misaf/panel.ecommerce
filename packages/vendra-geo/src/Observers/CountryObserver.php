<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraGeo\Models\Country;

final class CountryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(Country $country): void
    {
        $country->cities()->delete();
        $country->states()->delete();
    }
}
