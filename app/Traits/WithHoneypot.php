<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

trait WithHoneypot
{
    use UsesSpamProtection;

    public HoneypotData $extraFields;

    public function mountWithHoneypot(): void
    {
        $this->extraFields = new HoneypotData();
    }
}
