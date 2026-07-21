<?php

declare(strict_types=1);

use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages\CreateAttribute;
use Misaf\VendraAttribute\Models\Attribute;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('defaults new attributes to active, matching the schema default', function (): void {
    livewire(CreateAttribute::class)
        ->fillForm([
            'name' => 'Color',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Attribute::query()->sole())
        ->status->toBeTrue();
});
