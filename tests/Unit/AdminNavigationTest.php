<?php

declare(strict_types=1);

use App\Filament\Admin\Clusters\Settings\SettingsCluster;
use App\Providers\Filament\AdminPanelServiceProvider;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraActivityLog\Filament\Resources\ActivityLogResource;
use Misaf\VendraAffiliate\Filament\Clusters\AffiliatesCluster;
use Misaf\VendraAttribute\Filament\Clusters\AttributesCluster;
use Misaf\VendraAuthifyLog\Filament\Resources\AuthifyLogResource;
use Misaf\VendraBlog\Filament\Clusters\BlogsCluster;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;
use Misaf\VendraCurrency\Filament\Clusters\CurrenciesCluster;
use Misaf\VendraCustomPage\Filament\Clusters\CustomPagesCluster;
use Misaf\VendraFaq\Filament\Clusters\FaqsCluster;
use Misaf\VendraGeo\Filament\Clusters\GeoCluster;
use Misaf\VendraLanguage\Filament\Clusters\LanguagesCluster;
use Misaf\VendraMultimedia\Filament\Resources\MultimediaResource;
use Misaf\VendraNewsletter\Filament\Clusters\NewslettersCluster;
use Misaf\VendraPermission\Filament\Clusters\PermissionsCluster;
use Misaf\VendraProduct\Filament\Clusters\ProductsCluster;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;
use Misaf\VendraTagger\Filament\Resources\TaggerResource;
use Misaf\VendraTransaction\Filament\Clusters\TransactionsCluster;
use Misaf\VendraUser\Filament\Clusters\UsersCluster;

it('registers package-oriented navigation groups in a stable order', function (): void {
    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());
    $groups = $panel->getNavigationGroups();

    expect(array_values(array_map(
        static fn($group): ?string => $group->getLabel(),
        $groups,
    )))->toBe(array_map(
        static fn(NavigationGroup $group): string => $group->getLabel(),
        NavigationGroup::cases(),
    ));
});

it('resolves navigation group labels after the request locale changes', function (): void {
    app()->setLocale('en');

    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    app()->setLocale('fa');

    expect(array_values(array_map(
        static fn($group): ?string => $group->getLabel(),
        $panel->getNavigationGroups(),
    )))->toBe(array_map(
        static fn(NavigationGroup $group): string => $group->getLabel(),
        NavigationGroup::cases(),
    ));
});

it('orders packages predictably inside each navigation group', function (string $item, int $sort): void {
    expect($item::getNavigationSort())->toBe($sort);
})->with([
    'catalog / products'       => [ProductsCluster::class, 1],
    'catalog / attributes'     => [AttributesCluster::class, 2],
    'sales / transactions'     => [TransactionsCluster::class, 1],
    'sales / currencies'       => [CurrenciesCluster::class, 2],
    'sales / carts'            => [CartResource::class, 3],
    'customers / users'        => [UsersCluster::class, 1],
    'customers / permissions'  => [PermissionsCluster::class, 2],
    'content / blog'           => [BlogsCluster::class, 1],
    'content / custom pages'   => [CustomPagesCluster::class, 2],
    'content / faqs'           => [FaqsCluster::class, 3],
    'content / multimedia'     => [MultimediaResource::class, 4],
    'content / tags'           => [TaggerResource::class, 5],
    'marketing / affiliates'   => [AffiliatesCluster::class, 1],
    'marketing / newsletters'  => [NewslettersCluster::class, 2],
    'localization / languages' => [LanguagesCluster::class, 1],
    'localization / geography' => [GeoCluster::class, 2],
    'system / settings'        => [SettingsCluster::class, 1],
    'system / activity logs'   => [ActivityLogResource::class, 2],
    'system / auth logs'       => [AuthifyLogResource::class, 3],
]);

it('uses a distinct icon for every package navigation item', function (string $item, Heroicon $icon): void {
    expect($item::getNavigationIcon())->toBe($icon);
})->with([
    'products'      => [ProductsCluster::class, Heroicon::OutlinedSquares2x2],
    'attributes'    => [AttributesCluster::class, Heroicon::OutlinedAdjustmentsHorizontal],
    'transactions'  => [TransactionsCluster::class, Heroicon::OutlinedBanknotes],
    'currencies'    => [CurrenciesCluster::class, Heroicon::OutlinedCurrencyDollar],
    'carts'         => [CartResource::class, Heroicon::OutlinedShoppingCart],
    'users'         => [UsersCluster::class, Heroicon::OutlinedUsers],
    'permissions'   => [PermissionsCluster::class, Heroicon::OutlinedKey],
    'blog'          => [BlogsCluster::class, Heroicon::OutlinedNewspaper],
    'custom pages'  => [CustomPagesCluster::class, Heroicon::OutlinedDocumentText],
    'faqs'          => [FaqsCluster::class, Heroicon::OutlinedQuestionMarkCircle],
    'multimedia'    => [MultimediaResource::class, Heroicon::OutlinedPhoto],
    'tags'          => [TaggerResource::class, Heroicon::OutlinedTag],
    'affiliates'    => [AffiliatesCluster::class, Heroicon::OutlinedLink],
    'newsletters'   => [NewslettersCluster::class, Heroicon::OutlinedEnvelope],
    'languages'     => [LanguagesCluster::class, Heroicon::OutlinedLanguage],
    'geography'     => [GeoCluster::class, Heroicon::OutlinedMapPin],
    'settings'      => [SettingsCluster::class, Heroicon::OutlinedCog6Tooth],
    'activity logs' => [ActivityLogResource::class, Heroicon::OutlinedClipboardDocumentList],
    'auth logs'     => [AuthifyLogResource::class, Heroicon::OutlinedShieldCheck],
]);

it('renders package resources as top sub-navigation tabs', function (string $cluster): void {
    expect($cluster::getSubNavigationPosition())->toBe(SubNavigationPosition::Top);
})->with([
    AffiliatesCluster::class,
    AttributesCluster::class,
    BlogsCluster::class,
    CurrenciesCluster::class,
    CustomPagesCluster::class,
    FaqsCluster::class,
    GeoCluster::class,
    LanguagesCluster::class,
    NewslettersCluster::class,
    PermissionsCluster::class,
    ProductsCluster::class,
    SettingsCluster::class,
    TransactionsCluster::class,
    UsersCluster::class,
]);
