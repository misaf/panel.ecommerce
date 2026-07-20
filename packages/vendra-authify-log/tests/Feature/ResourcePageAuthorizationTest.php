<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraAuthifyLog\Database\Factories\AuthifyLogFactory;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\Pages\ViewAuthifyLog;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the view authify log page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $authifyLog = AuthifyLogFactory::new()->createOne();

    livewire(ViewAuthifyLog::class, ['record' => $authifyLog->getKey()])
        ->assertOk();
});
