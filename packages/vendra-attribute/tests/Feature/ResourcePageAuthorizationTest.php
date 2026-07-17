<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraAttribute\Database\Factories\AttributeFactory;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\CreateAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\EditAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\ListAttributes;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('renders the create attribute page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateAttribute::class)
        ->assertOk();
});

it('renders the edit attribute page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $attribute = AttributeFactory::new()->createOne();

    livewire(EditAttribute::class, ['record' => $attribute->getKey()])
        ->assertOk();
});

it('renders the reorderable attributes table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $attribute = AttributeFactory::new()->createOne();

    livewire(ListAttributes::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$attribute]);
});
