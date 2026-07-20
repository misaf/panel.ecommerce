<?php

declare(strict_types=1);

use App\Filament\User\Clusters\Billings\Deposit\DepositCluster\DepositCluster;
use App\Filament\User\Clusters\Billings\Withdrawal\WithdrawalCluster\WithdrawalCluster;
use App\Providers\Filament\AdminPanelServiceProvider;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Lang;
use Misaf\VendraActivityLog\Filament\Clusters\Resources\ActivityLogResource;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\AuthifyLogResource;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\CartResource;
use Misaf\VendraMultimedia\Filament\Clusters\Resources\MultimediaResource;
use Misaf\VendraSupport\Filament\Clusters\CatalogCluster;
use Misaf\VendraSupport\Filament\Clusters\ContentCluster;
use Misaf\VendraSupport\Filament\Clusters\CustomersCluster;
use Misaf\VendraSupport\Filament\Clusters\LocalizationCluster;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;
use Misaf\VendraSupport\Filament\Clusters\SalesCluster;
use Misaf\VendraSupport\Filament\Clusters\SystemCluster;
use Misaf\VendraSupport\Filament\Navigation\NavigationGroup;
use Misaf\VendraSupport\Filament\Navigation\NavigationPriority;
use Misaf\VendraTagger\Filament\Clusters\Resources\Taggers\TaggerResource;

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

it('orders navigation resources by centralized priority', function (
    string $resource,
    NavigationPriority $priority,
): void {
    expect($resource::getNavigationSort())->toBe($priority->value);
})->with([
    'products'                   => [Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource::class, NavigationPriority::Products],
    'product categories'         => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource::class, NavigationPriority::ProductCategories],
    'product prices'             => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductPrices\ProductPriceResource::class, NavigationPriority::ProductPrices],
    'attributes'                 => [Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class, NavigationPriority::Attributes],
    'transactions'               => [Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class, NavigationPriority::Transactions],
    'transaction gateways'       => [Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class, NavigationPriority::TransactionGateways],
    'wallets'                    => [Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource::class, NavigationPriority::Wallets],
    'currencies'                 => [Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource::class, NavigationPriority::Currencies],
    'currency categories'        => [Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource::class, NavigationPriority::CurrencyCategories],
    'carts'                      => [CartResource::class, NavigationPriority::Carts],
    'users'                      => [Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class, NavigationPriority::Users],
    'user profiles'              => [Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::class, NavigationPriority::UserProfiles],
    'roles'                      => [Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource::class, NavigationPriority::Roles],
    'permissions'                => [Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource::class, NavigationPriority::Permissions],
    'blog posts'                 => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource::class, NavigationPriority::BlogPosts],
    'blog post categories'       => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource::class, NavigationPriority::BlogPostCategories],
    'custom pages'               => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource::class, NavigationPriority::CustomPages],
    'custom page categories'     => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource::class, NavigationPriority::CustomPageCategories],
    'faqs'                       => [Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource::class, NavigationPriority::Faqs],
    'faq categories'             => [Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource::class, NavigationPriority::FaqCategories],
    'multimedia'                 => [MultimediaResource::class, NavigationPriority::Multimedia],
    'tags'                       => [TaggerResource::class, NavigationPriority::Tags],
    'affiliates'                 => [Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource::class, NavigationPriority::Affiliates],
    'affiliate commissions'      => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource::class, NavigationPriority::AffiliateCommissions],
    'affiliate payouts'          => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource::class, NavigationPriority::AffiliatePayouts],
    'newsletters'                => [Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class, NavigationPriority::Newsletters],
    'newsletter subscribers'     => [Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class, NavigationPriority::NewsletterSubscribers],
    'languages'                  => [Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource::class, NavigationPriority::Languages],
    'language lines'             => [Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource::class, NavigationPriority::LanguageLines],
    'general settings'           => [App\Filament\Admin\Pages\ManageGeneralSettings::class, NavigationPriority::GeneralSettings],
    'activity logs'              => [ActivityLogResource::class, NavigationPriority::ActivityLogs],
    'authentication logs'        => [AuthifyLogResource::class, NavigationPriority::AuthenticationLogs],
]);

it('assigns every navigation priority a unique sort value', function (): void {
    $values = array_column(NavigationPriority::cases(), 'value');

    expect(array_unique($values))->toHaveCount(count($values));
});

it('uses concise singular and plural resource labels in every configured locale', function (
    string $resource,
    string $singularKey,
    string $pluralKey,
): void {
    $originalLocale = app()->getLocale();

    try {
        foreach (['en', 'de', 'fa'] as $locale) {
            app()->setLocale($locale);

            expect(Lang::has($singularKey, $locale))->toBeTrue()
                ->and(Lang::has($pluralKey, $locale))->toBeTrue()
                ->and($resource::getModelLabel())->toBe(__($singularKey))
                ->and($resource::getNavigationLabel())->toBe(__($pluralKey))
                ->and($resource::getPluralModelLabel())->toBe(__($pluralKey))
                ->and(mb_strlen($resource::getNavigationLabel()))->toBeLessThanOrEqual(24);
        }
    } finally {
        app()->setLocale($originalLocale);
    }
})->with([
    'activity logs'          => [ActivityLogResource::class, 'vendra-activity-log::navigation.activity_log', 'vendra-activity-log::navigation.activity_logs'],
    'affiliates'             => [Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource::class, 'vendra-affiliate::navigation.affiliate', 'vendra-affiliate::navigation.affiliates'],
    'affiliate commissions'  => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource::class, 'vendra-affiliate::navigation.affiliate_commission', 'vendra-affiliate::navigation.affiliate_commissions'],
    'affiliate payouts'      => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource::class, 'vendra-affiliate::navigation.affiliate_payout', 'vendra-affiliate::navigation.affiliate_payouts'],
    'attributes'             => [Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class, 'vendra-attribute::navigation.attribute', 'vendra-attribute::navigation.attributes'],
    'authentication logs'    => [AuthifyLogResource::class, 'vendra-authify-log::navigation.authify_log', 'vendra-authify-log::navigation.authify_logs'],
    'blog posts'             => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource::class, 'vendra-blog::navigation.blog_post', 'vendra-blog::navigation.blog_posts'],
    'blog categories'        => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource::class, 'vendra-blog::navigation.blog_post_category', 'vendra-blog::navigation.blog_post_categories'],
    'carts'                  => [CartResource::class, 'vendra-cart::navigation.cart', 'vendra-cart::navigation.carts'],
    'currencies'             => [Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource::class, 'vendra-currency::navigation.currency', 'vendra-currency::navigation.currencies'],
    'currency categories'    => [Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource::class, 'vendra-currency::navigation.currency_category', 'vendra-currency::navigation.currency_categories'],
    'custom pages'           => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource::class, 'vendra-custom-page::navigation.custom_page', 'vendra-custom-page::navigation.custom_pages'],
    'page categories'        => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource::class, 'vendra-custom-page::navigation.custom_page_category', 'vendra-custom-page::navigation.custom_page_categories'],
    'faqs'                   => [Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource::class, 'vendra-faq::navigation.faq', 'vendra-faq::navigation.faqs'],
    'faq categories'         => [Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource::class, 'vendra-faq::navigation.faq_category', 'vendra-faq::navigation.faq_categories'],
    'languages'              => [Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource::class, 'vendra-language::navigation.language', 'vendra-language::navigation.languages'],
    'translations'           => [Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource::class, 'vendra-language::navigation.language_line', 'vendra-language::navigation.language_lines'],
    'media'                  => [MultimediaResource::class, 'vendra-multimedia::navigation.media_item', 'vendra-multimedia::navigation.media_items'],
    'newsletters'            => [Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class, 'vendra-newsletter::navigation.newsletter', 'vendra-newsletter::navigation.newsletters'],
    'subscribers'            => [Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class, 'vendra-newsletter::navigation.newsletter_subscriber', 'vendra-newsletter::navigation.newsletter_subscribers'],
    'permissions'            => [Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource::class, 'vendra-permission::navigation.permission', 'vendra-permission::navigation.permissions'],
    'roles'                  => [Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource::class, 'vendra-permission::navigation.role', 'vendra-permission::navigation.roles'],
    'products'               => [Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource::class, 'vendra-product::navigation.product', 'vendra-product::navigation.products'],
    'product categories'     => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource::class, 'vendra-product::navigation.product_category', 'vendra-product::navigation.product_categories'],
    'product prices'         => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductPrices\ProductPriceResource::class, 'vendra-product::navigation.product_price', 'vendra-product::navigation.product_prices'],
    'tags'                   => [TaggerResource::class, 'vendra-tagger::navigation.tagger', 'vendra-tagger::navigation.taggers'],
    'transactions'           => [Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class, 'vendra-transaction::navigation.transaction', 'vendra-transaction::navigation.transactions'],
    'payment gateways'       => [Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class, 'vendra-transaction::navigation.transaction_gateway', 'vendra-transaction::navigation.transaction_gateways'],
    'users'                  => [Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class, 'vendra-user::navigation.user', 'vendra-user::navigation.users'],
    'user profiles'          => [Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::class, 'vendra-user-profile::navigation.user_profile', 'vendra-user-profile::navigation.user_profiles'],
    'wallets'                => [Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource::class, 'vendra-transaction::navigation.wallet', 'vendra-transaction::navigation.wallets'],
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

it('keeps cluster resources ungrouped so priority controls visible order', function (string $resource): void {
    expect($resource::getNavigationGroup())->toBeNull();
})->with([
    'products'                 => Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource::class,
    'product categories'       => Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource::class,
    'product prices'           => Misaf\VendraProduct\Filament\Clusters\Resources\ProductPrices\ProductPriceResource::class,
    'attributes'               => Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class,
    'transactions'             => Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class,
    'transaction gateways'     => Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class,
    'wallets'                  => Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource::class,
    'currencies'               => Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource::class,
    'currency categories'      => Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource::class,
    'carts'                    => CartResource::class,
    'users'                    => Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class,
    'user profiles'            => Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::class,
    'roles'                    => Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource::class,
    'permissions'              => Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource::class,
    'blog posts'               => Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource::class,
    'blog post categories'     => Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource::class,
    'custom pages'             => Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource::class,
    'custom page categories'   => Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource::class,
    'faqs'                     => Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource::class,
    'faq categories'           => Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource::class,
    'multimedia'               => MultimediaResource::class,
    'tags'                     => TaggerResource::class,
    'affiliates'               => Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource::class,
    'affiliate commissions'    => Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource::class,
    'affiliate payouts'        => Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource::class,
    'newsletters'              => Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class,
    'newsletter subscribers'   => Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class,
    'languages'                => Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource::class,
    'language lines'           => Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource::class,
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
    'taggers'                 => [TaggerResource::class, Heroicon::OutlinedHashtag],
    'transaction gateways'    => [Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class, Heroicon::OutlinedCreditCard],
    'transactions'            => [Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class, Heroicon::OutlinedArrowsRightLeft],
    'user profiles'           => [Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::class, Heroicon::OutlinedIdentification],
    'users'                   => [Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class, Heroicon::OutlinedUserGroup],
    'wallets'                 => [Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource::class, Heroicon::OutlinedWallet],
]);
