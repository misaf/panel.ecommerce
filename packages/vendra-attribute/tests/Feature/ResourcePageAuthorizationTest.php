<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraAttribute\Database\Factories\AttributeFactory;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\CreateAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\EditAttribute;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\ListAttributes;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\ViewAttribute;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
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

it('renders the view attribute page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $attribute = AttributeFactory::new()->createOne();

    livewire(ViewAttribute::class, ['record' => $attribute->getKey()])
        ->assertOk()
        ->assertSee($attribute->name);
});
