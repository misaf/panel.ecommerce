<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;
use Misaf\VendraTagger\Database\Factories\TaggerFactory;
use Misaf\VendraTagger\Filament\Clusters\Resources\Pages\CreateTagger;
use Misaf\VendraTagger\Filament\Clusters\Resources\Pages\EditTagger;
use Misaf\VendraTagger\Filament\Clusters\Resources\Pages\ListTaggers;
use Misaf\VendraTagger\Filament\Clusters\Resources\Pages\ViewTagger;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('renders the create tagger page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(CreateTagger::class)
        ->assertOk();
});

it('renders the edit tagger page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $tagger = TaggerFactory::new()->createOne();

    livewire(EditTagger::class, ['record' => $tagger->getKey()])
        ->assertOk();
});

it('renders the view tagger page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $tagger = TaggerFactory::new()->createOne();

    livewire(ViewTagger::class, ['record' => $tagger->getKey()])
        ->assertOk();
});

it('renders the reorderable taggers table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $tagger = TaggerFactory::new()->createOne();

    livewire(ListTaggers::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$tagger]);
});
