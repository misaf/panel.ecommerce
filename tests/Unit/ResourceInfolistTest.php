<?php

declare(strict_types=1);

use Filament\Infolists\Components\Entry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Misaf\VendraActivityLog\Filament\Clusters\Resources\ActivityLogResource;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource;
use Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource;
use Misaf\VendraAuthifyLog\Filament\Clusters\Resources\AuthifyLogResource;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource;
use Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\CartResource;
use Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource;
use Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\CurrencyCategoryResource;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource;
use Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource;
use Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource;
use Misaf\VendraMultimedia\Filament\Clusters\Resources\MultimediaResource;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource;
use Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource;
use Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource;
use Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource;
use Misaf\VendraTagger\Filament\Clusters\Resources\TaggerResource;
use Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource;
use Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource;
use Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource;
use Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource;
use Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource;

it('uses an infolist for every resource view page', function (string $resource): void {
    if ( ! is_subclass_of($resource, Resource::class)) {
        throw new InvalidArgumentException("{$resource} is not a Filament resource.");
    }

    $schema = configuredResourceInfolist($resource);
    $components = $schema->getComponents(withHidden: true);

    expect($components)->not->toBeEmpty();

    foreach ($components as $component) {
        expect($component)->toBeInstanceOf(Entry::class);
    }
})->with([
    ActivityLogResource::class,
    AffiliateCommissionResource::class,
    AffiliatePayoutResource::class,
    AffiliateResource::class,
    AuthifyLogResource::class,
    BlogPostCategoryResource::class,
    BlogPostResource::class,
    CartResource::class,
    CurrencyResource::class,
    CurrencyCategoryResource::class,
    CustomPageCategoryResource::class,
    CustomPageResource::class,
    FaqCategoryResource::class,
    FaqResource::class,
    LanguageLineResource::class,
    LanguageResource::class,
    MultimediaResource::class,
    NewsletterSubscriberResource::class,
    NewsletterResource::class,
    PermissionResource::class,
    RoleResource::class,
    ProductCategoryResource::class,
    ProductResource::class,
    TaggerResource::class,
    TransactionGatewayResource::class,
    TransactionResource::class,
    WalletResource::class,
    UserProfileResource::class,
    UserResource::class,
]);

/** @param class-string<Resource> $resource */
function configuredResourceInfolist(string $resource): Schema
{
    return $resource::infolist(Schema::make());
}
