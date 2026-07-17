<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Concerns;

use Filament\Forms\Components\Select;
use Misaf\VendraSupport\Support\TagIntegration;

trait InteractsWithTagFields /** @phpstan-ignore trait.unused */
{
    /**
     * Build the shared tags select, or nothing when no tag provider is installed.
     *
     * @return list<Select>
     */
    protected static function tagFields(): array
    {
        if ( ! TagIntegration::isAvailable()) {
            return [];
        }

        return [
            Select::make('tags')
                ->columnSpanFull()
                ->label(__('vendra-support::attributes.tags'))
                ->multiple()
                ->native(false)
                ->preload()
                ->relationship('tags', 'name'),
        ];
    }
}
