<?php

declare(strict_types=1);

use App\Filament\Console\Resources\Resellers\Pages\EditReseller;
use App\Filament\Console\Widgets\ConsoleOverview;
use App\Filament\Reseller\Pages\Auth\Login;
use App\Models\ConsoleUser;
use App\Models\Reseller;
use App\Models\ResellerUser;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
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

it('changes a reseller owner password through the edit page action', function (): void {
    $admin = actingConsoleAdmin();

    $reseller = Reseller::factory()->create();
    $owner = ResellerUser::factory()
        ->forReseller($reseller->getKey())
        ->create();
    $originalRememberToken = $owner->getRememberToken();

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->assertActionVisible('changeOwnerPassword')
        ->assertActionEnabled('changeOwnerPassword')
        ->callAction('changeOwnerPassword', [
            'password'              => 'NewSecure123',
            'password_confirmation' => 'NewSecure123',
        ])
        ->assertHasNoActionErrors()
        ->assertNotified();

    $owner->refresh();

    expect(Hash::check('NewSecure123', $owner->password))->toBeTrue()
        ->and($owner->getRememberToken())->not->toBe($originalRememberToken)
        ->and(auth('console')->user()?->is($admin))->toBeTrue();

    Filament::setCurrentPanel(Filament::getPanel('reseller'));

    livewire(Login::class)
        ->fillForm([
            'email'    => $owner->email,
            'password' => 'NewSecure123',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    expect(auth('reseller')->user()?->is($owner))->toBeTrue();
});

it('requires confirmation when changing a reseller owner password', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();
    $owner = ResellerUser::factory()
        ->forReseller($reseller->getKey())
        ->create();
    $originalPassword = $owner->password;

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->callAction('changeOwnerPassword', [
            'password'              => 'NewSecure123',
            'password_confirmation' => 'Different123',
        ])
        ->assertHasActionErrors(['password' => 'confirmed']);

    expect($owner->fresh()?->password)->toBe($originalPassword);
});

it('shows why a reseller without an owner cannot change its password yet', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->assertActionVisible('createOwnerAccount')
        ->assertActionVisible('changeOwnerPassword')
        ->assertActionDisabled('changeOwnerPassword');
});

it('creates an owner login for an existing reseller', function (): void {
    actingConsoleAdmin();

    $reseller = Reseller::factory()->create();

    livewire(EditReseller::class, ['record' => $reseller->getKey()])
        ->callAction('createOwnerAccount', [
            'username'              => 'owner_login',
            'email'                 => 'owner@existing.test',
            'password'              => 'Secure123',
            'password_confirmation' => 'Secure123',
        ])
        ->assertHasNoActionErrors()
        ->assertNotified();

    $owner = $reseller->ownerUser()->sole();

    expect($owner)->toBeInstanceOf(ResellerUser::class)
        ->and($owner->email)->toBe('owner@existing.test')
        ->and(Hash::check('Secure123', $owner->password))->toBeTrue();
});

it('renders the console overview widget', function (): void {
    actingConsoleAdmin();
    Reseller::factory()->count(2)->create();

    livewire(ConsoleOverview::class)->assertOk();
});
