<?php

declare(strict_types=1);

use App\Filament\Platform\Resources\Accounts\Pages\EditAccount;
use App\Filament\Platform\Widgets\PlatformOverview;
use Filament\Facades\Filament;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

function actingPlatformAdmin(): User
{
    $admin = User::factory()->forTenant(createTestTenant())->platformAdmin()->create();
    actingAs($admin);
    Filament::setCurrentPanel(Filament::getPanel('platform'));

    return $admin;
}

it('changes an account plan through the edit page action', function (): void {
    actingPlatformAdmin();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory()->maxWebsites(2))->create();
    $newPlan = Plan::factory()->maxWebsites(5)->create();

    livewire(EditAccount::class, ['record' => $account->getKey()])
        ->callAction('changePlan', ['plan_id' => $newPlan->getKey()]);

    expect($account->activeSubscription()?->plan_id)->toBe($newPlan->getKey());
});

it('blocks a plan change that cannot hold the current websites', function (): void {
    actingPlatformAdmin();

    $account = Account::factory()->create();
    $currentPlan = Plan::factory()->maxWebsites(2)->create();
    Subscription::factory()->for($account)->for($currentPlan)->create();
    Tenant::factory()->count(2)->create(['account_id' => $account->getKey()]);

    livewire(EditAccount::class, ['record' => $account->getKey()])
        ->callAction('changePlan', ['plan_id' => Plan::factory()->maxWebsites(1)->create()->getKey()]);

    expect($account->activeSubscription()?->plan_id)->toBe($currentPlan->getKey());
});

it('renews the subscription through the edit page action', function (): void {
    actingPlatformAdmin();

    $account = Account::factory()->create();
    Subscription::factory()->for($account)->for(Plan::factory())->create();

    livewire(EditAccount::class, ['record' => $account->getKey()])
        ->callAction('renew');

    expect($account->subscriptions()->count())->toBe(2)
        ->and($account->subscriptions()->active()->count())->toBe(1);
});

it('renders the platform overview widget', function (): void {
    actingPlatformAdmin();
    Account::factory()->count(2)->create();

    livewire(PlatformOverview::class)->assertOk();
});
