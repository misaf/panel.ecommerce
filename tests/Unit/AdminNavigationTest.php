<?php

declare(strict_types=1);

use App\Filament\User\Clusters\Billings\Deposit\DepositCluster\DepositCluster;
use App\Filament\User\Clusters\Billings\Withdrawal\WithdrawalCluster\WithdrawalCluster;
use App\Providers\Filament\AdminPanelServiceProvider;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraActivityLog\Filament\Resources\ActivityLogResource;
use Misaf\VendraAuthifyLog\Filament\Resources\AuthifyLogResource;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;
use Misaf\VendraMultimedia\Filament\Resources\MultimediaResource;
use Misaf\VendraSupport\Filament\Clusters\CatalogCluster;
use Misaf\VendraSupport\Filament\Clusters\ContentCluster;
use Misaf\VendraSupport\Filament\Clusters\CustomersCluster;
use Misaf\VendraSupport\Filament\Clusters\LocalizationCluster;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;
use Misaf\VendraSupport\Filament\Clusters\SalesCluster;
use Misaf\VendraSupport\Filament\Clusters\SystemCluster;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;
use Misaf\VendraTagger\Filament\Resources\TaggerResource;

it('uses domain clusters as top-level navigation without redundant groups', function (): void {
    $panel = (new AdminPanelServiceProvider(app()))->panel(Panel::make());

    expect($panel->getNavigationGroups())->toBe([]);
});

it('orders domain clusters predictably', function (string $cluster, int $sort): void {
    expect($cluster::getNavigationSort())->toBe($sort);
})->with([
    'catalog'      => [CatalogCluster::class, 1],
    'sales'        => [SalesCluster::class, 2],
    'customers'    => [CustomersCluster::class, 3],
    'content'      => [ContentCluster::class, 4],
    'marketing'    => [MarketingCluster::class, 5],
    'localization' => [LocalizationCluster::class, 6],
    'system'       => [SystemCluster::class, 7],
]);

it('uses the domain label and icon for each cluster', function (
    string $cluster,
    NavigationGroup $domain,
    Heroicon $icon,
): void {
    expect($cluster::getNavigationLabel())->toBe($domain->getLabel())
        ->and($cluster::getClusterBreadcrumb())->toBe($domain->getLabel())
        ->and($cluster::getNavigationIcon())->toBe($icon)
        ->and($cluster::getNavigationGroup())->toBeNull();
})->with([
    'catalog'      => [CatalogCluster::class, NavigationGroup::Catalog, Heroicon::OutlinedSquares2x2],
    'sales'        => [SalesCluster::class, NavigationGroup::Sales, Heroicon::OutlinedBanknotes],
    'customers'    => [CustomersCluster::class, NavigationGroup::Customers, Heroicon::OutlinedUsers],
    'content'      => [ContentCluster::class, NavigationGroup::Content, Heroicon::OutlinedNewspaper],
    'marketing'    => [MarketingCluster::class, NavigationGroup::Marketing, Heroicon::OutlinedMegaphone],
    'localization' => [LocalizationCluster::class, NavigationGroup::Localization, Heroicon::OutlinedLanguage],
    'system'       => [SystemCluster::class, NavigationGroup::System, Heroicon::OutlinedCog6Tooth],
]);

it('renders domain resources as top sub-navigation tabs', function (string $cluster): void {
    expect($cluster::getSubNavigationPosition())->toBe(SubNavigationPosition::Top);
})->with([
    CatalogCluster::class,
    SalesCluster::class,
    CustomersCluster::class,
    ContentCluster::class,
    MarketingCluster::class,
    LocalizationCluster::class,
    SystemCluster::class,
]);

it('uses semantic icons for billing clusters', function (string $cluster, Heroicon $icon): void {
    expect($cluster::getNavigationIcon())->toBe($icon);
})->with([
    'deposit'    => [DepositCluster::class, Heroicon::OutlinedArrowDownTray],
    'withdrawal' => [WithdrawalCluster::class, Heroicon::OutlinedArrowUpTray],
]);

it('does not group resources that would be alone in a navigation group', function (string $resource): void {
    expect($resource::getNavigationGroup())->toBeNull();
})->with([
    'attributes' => Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class,
]);

it('groups newsletters and subscribers together', function (string $resource): void {
    expect($resource::getNavigationGroup())->toBe(__('vendra-newsletter::navigation.newsletter_management'));
})->with([
    'newsletter subscribers' => Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class,
    'newsletters'            => Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class,
]);

it('uses semantic icons for domain resources', function (string $resource, Heroicon $icon): void {
    expect($resource::getNavigationIcon())->toBe($icon);
})->with([
    'activity logs'           => [ActivityLogResource::class, Heroicon::OutlinedClipboardDocumentList],
    'affiliate commissions'   => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource::class, Heroicon::OutlinedReceiptPercent],
    'affiliate payouts'       => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource::class, Heroicon::OutlinedBanknotes],
    'affiliates'              => [Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource::class, Heroicon::OutlinedLink],
    'attributes'              => [Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class, Heroicon::OutlinedAdjustmentsHorizontal],
    'authify logs'            => [AuthifyLogResource::class, Heroicon::OutlinedShieldCheck],
    'blog post categories'    => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource::class, Heroicon::OutlinedFolder],
    'blog posts'              => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource::class, Heroicon::OutlinedDocumentText],
    'carts'                   => [CartResource::class, Heroicon::OutlinedShoppingCart],
    'cities'                  => [Misaf\VendraGeo\Filament\Clusters\Resources\Cities\CityResource::class, Heroicon::OutlinedBuildingOffice2],
    'countries'               => [Misaf\VendraGeo\Filament\Clusters\Resources\Countries\CountryResource::class, Heroicon::OutlinedGlobeAlt],
    'currencies'              => [Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource::class, Heroicon::OutlinedBanknotes],
    'currency categories'     => [Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource::class, Heroicon::OutlinedCircleStack],
    'custom page categories'  => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource::class, Heroicon::OutlinedFolder],
    'custom pages'            => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource::class, Heroicon::OutlinedDocument],
    'faq categories'          => [Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource::class, Heroicon::OutlinedFolder],
    'faqs'                    => [Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource::class, Heroicon::OutlinedQuestionMarkCircle],
    'language lines'          => [Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource::class, Heroicon::OutlinedChatBubbleBottomCenterText],
    'languages'               => [Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource::class, Heroicon::OutlinedLanguage],
    'multimedia'              => [MultimediaResource::class, Heroicon::OutlinedPhoto],
    'newsletter subscribers'  => [Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class, Heroicon::OutlinedUserGroup],
    'newsletters'             => [Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class, Heroicon::OutlinedEnvelope],
    'permissions'             => [Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource::class, Heroicon::OutlinedKey],
    'product categories'      => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource::class, Heroicon::OutlinedSquares2x2],
    'product prices'          => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductPrices\ProductPriceResource::class, Heroicon::OutlinedCurrencyDollar],
    'products'                => [Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource::class, Heroicon::OutlinedCube],
    'roles'                   => [Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource::class, Heroicon::OutlinedShieldCheck],
    'states'                  => [Misaf\VendraGeo\Filament\Clusters\Resources\States\StateResource::class, Heroicon::OutlinedMap],
    'taggers'                 => [TaggerResource::class, Heroicon::OutlinedHashtag],
    'transaction gateways'    => [Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class, Heroicon::OutlinedCreditCard],
    'transactions'            => [Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class, Heroicon::OutlinedArrowsRightLeft],
    'user profiles'           => [Misaf\VendraUserProfile\Filament\Resources\UserProfileResource::class, Heroicon::OutlinedIdentification],
    'users'                   => [Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class, Heroicon::OutlinedUserGroup],
]);
