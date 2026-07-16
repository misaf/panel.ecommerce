<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Tests\Support;

use Closure;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Features\ModuleEnabled;
use Misaf\VendraUserProfile\Filament\Resources\UserProfileResource;

final class UserProfileModuleTestContext implements TenantResolver
{
    private function __construct(private readonly TestTenant $tenant) {}

    public static function createCurrentTenant(): TestTenant
    {
        $tenant = new TestTenant();
        $tenant->forceFill([
            'id'   => 1,
            'name' => 'Test Tenant',
        ]);
        $tenant->exists = true;

        app()->instance(TenantResolver::class, new self($tenant));

        return $tenant;
    }

    public static function createUser(): User
    {
        return User::query()->create([
            'username' => 'super-admin',
            'email'    => 'super-admin@example.test',
            'password' => Hash::make('password'),
        ]);
    }

    public static function setUpFilamentAdminContext(): TestTenant
    {
        $tenant = self::createCurrentTenant();
        $user = self::createUser();

        Feature::for($tenant)->activate(ModuleEnabled::class);
        Gate::before(static fn(): bool => true);

        app(PanelRegistry::class)->register(
            Panel::make()
                ->default()
                ->id('admin')
                ->path('admin')
                ->resources([UserProfileResource::class])
        );

        Table::configureUsing(fn(Table $table): Table => $table
            ->paginationPageOptions([10, 25, 50])
            ->deferLoading());

        Filament::setCurrentPanel('admin');
        Livewire::actingAs($user);
        Filament::bootCurrentPanel();

        app('url')->resolveMissingNamedRoutesUsing(static fn(): string => '/');

        return $tenant;
    }

    public function available(): bool
    {
        return true;
    }

    public function current(): TestTenant
    {
        return $this->tenant;
    }

    public function currentId(): int
    {
        return $this->tenant->id;
    }

    public function modelClass(): string
    {
        return TestTenant::class;
    }

    public function findByKeyOrSlug(int|string $tenant): TestTenant
    {
        return $this->tenant;
    }

    public function makeCurrent(Model|int|string $tenant): bool
    {
        return true;
    }

    public function execute(Model|int|string $tenant, Closure $callback): mixed
    {
        return $callback();
    }

    public function eachTenant(Closure $callback): void
    {
        $callback();
    }

    public function searchOptions(string $value, int $limit = 10): array
    {
        return [$this->currentId() => $this->tenant->name];
    }
}
