<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties\Pages;

use App\Filament\Reseller\Resources\Properties\PropertyResource;
use App\Models\Reseller;
use App\Support\PropertyQuota;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    public function getSubheading(): ?string
    {
        $remaining = $this->remainingProperties();

        return null === $remaining
            ? null
            : __('console.remaining_properties') . ': ' . $remaining;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->disabled(fn(): bool => (int) $this->remainingProperties() <= 0),
        ];
    }

    private function remainingProperties(): ?int
    {
        $resellerId = PropertyResource::currentResellerId();

        if (null === $resellerId) {
            return null;
        }

        $reseller = Reseller::query()->find($resellerId);

        if (null === $reseller) {
            return null;
        }

        return app(PropertyQuota::class)->remainingProperties($reseller);
    }
}
