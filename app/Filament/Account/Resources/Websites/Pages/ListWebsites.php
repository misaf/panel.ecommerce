<?php

declare(strict_types=1);

namespace App\Filament\Account\Resources\Websites\Pages;

use App\Filament\Account\Resources\Websites\WebsiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Support\WebsiteQuota;

final class ListWebsites extends ListRecords
{
    protected static string $resource = WebsiteResource::class;

    public function getSubheading(): ?string
    {
        $remaining = $this->remainingWebsites();

        return null === $remaining
            ? null
            : __('platform.remaining_websites') . ': ' . $remaining;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->disabled(fn(): bool => (int) $this->remainingWebsites() <= 0),
        ];
    }

    private function remainingWebsites(): ?int
    {
        $accountId = WebsiteResource::currentAccountId();

        if (null === $accountId) {
            return null;
        }

        $account = Account::query()->find($accountId);

        if (null === $account) {
            return null;
        }

        return app(WebsiteQuota::class)->remainingWebsites($account);
    }
}
