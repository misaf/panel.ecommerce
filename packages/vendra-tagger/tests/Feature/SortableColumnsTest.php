<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;
use Misaf\VendraTagger\Database\Factories\TaggerFactory;
use Misaf\VendraTagger\Filament\Clusters\Resources\Pages\ListTaggers;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('sorts the taggers table by every sortable column following the stored values', function (): void {
    $first = TaggerFactory::new()->createOne(['created_at' => now()->subDays(2)]);
    $second = TaggerFactory::new()->createOne(['created_at' => now()->subDay()]);

    expect(livewire(ListTaggers::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
