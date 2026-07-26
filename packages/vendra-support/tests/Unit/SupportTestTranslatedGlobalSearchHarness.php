<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedGlobalSearch;

final class SupportTestTranslatedGlobalSearchHarness
{
    use InteractsWithTranslatedGlobalSearch;

    /**
     * @return array<int, string>
     */
    protected static function translatableGlobalSearchAttributes(): array
    {
        return ['name', 'slug'];
    }
}
