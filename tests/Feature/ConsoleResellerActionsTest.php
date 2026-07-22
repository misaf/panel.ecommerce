<?php

declare(strict_types=1);

use App\Filament\Console\Resources\Resellers\Pages\EditReseller;
use App\Filament\Console\Widgets\ConsoleOverview;
use App\Models\ConsoleUser;
use App\Models\Reseller;
use Filament\Facades\Filament;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function actingConsoleAdmin(): ConsoleUser
{
    $admin = ConsoleUser::factory()->create();
    actingAs($admin, 'console');
    Filament::setCurrentPanel(Filament::getPanel('console'));

    return $admin;
}

it('changes a reseller plan through the edit page action', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory()->maxUnits(2))->create();
    $newPlan = Plan::factory()->maxUnits(5)->create();

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->callAction('changePlan', ['plan_id' => $newPlan->getKey()]);

    expect($reseller->activeSubscription()?->plan_id)->toBe($newPlan->getKey());
});

it('blocks a plan change that cannot hold the current properties', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();
    $currentPlan = Plan::factory()->maxUnits(2)->create();
    Subscription::factory()->forSubscriber($reseller)->for($currentPlan)->create();
    Tenant::factory()->count(2)->create(['reseller_id' => $reseller->getKey()]);

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->callAction('changePlan', ['plan_id' => Plan::factory()->maxUnits(1)->create()->getKey()]);

    expect($reseller->activeSubscription()?->plan_id)->toBe($currentPlan->getKey());
});

it('renews the subscription through the edit page action', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();
    Subscription::factory()->forSubscriber($reseller)->for(Plan::factory())->create();

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->callAction('renew');

    expect($reseller->subscriptions()->count())->toBe(2)
        ->and($reseller->subscriptions()->active()->count())->toBe(1);
});

it('renders the console overview widget', function (): void {
    actingConsoleAdmin();
    Reseller::factory()->count(2)->create();

    livewire(ConsoleOverview::class)->assertOk();
});
