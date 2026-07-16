<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Misaf\VendraSupport\Filament\Concerns\InteractsWithTranslatedTableRecords;

final class SupportTestTranslatedTableHarness
{
    use InteractsWithTranslatedTableRecords;

    public static function resolveTranslatedAttribute(Model $record, string $attribute, Livewire $livewire): string
    {
        return static::translatedAttribute($record, $attribute, $livewire);
    }

    public static function resolveIntegerAttribute(Model $record, string $attribute): int
    {
        return static::integerAttribute($record, $attribute);
    }
}
