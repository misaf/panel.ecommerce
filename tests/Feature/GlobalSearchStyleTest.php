<?php

declare(strict_types=1);

use Filament\Resources\Resource;
use Misaf\VendraProduct\Filament\Clusters\Resources\ProductPrices\ProductPriceResource;

beforeEach(function (): void {
    app()->setLocale('en');
});

it('configures every host and package resource with the normalized global search style', function (string $resource, bool $hasCustomResultUrl): void {
    expect(is_subclass_of($resource, Resource::class))->toBeTrue();

    $attributes = $resource::getGloballySearchableAttributes();
    $descriptionAttributes = array_filter(
        $attributes,
        static fn(string $attribute): bool => str_contains($attribute, 'description'),
    );
    $declaresCustomResultUrl = (new ReflectionMethod($resource, 'getGlobalSearchResultUrl'))
        ->getDeclaringClass()
        ->getName() === $resource;

    expect($attributes)
        ->not->toBeEmpty()
        ->and($descriptionAttributes)->toBeEmpty()
        ->and(
            $resource::hasPage('view')
            || $resource::hasPage('edit')
            || ($hasCustomResultUrl && $declaresCustomResultUrl),
        )
        ->toBeTrue();
})->with('searchable resources');

it('keeps the page-less product price resource out of global search', function (): void {
    $isGloballySearchable = new ReflectionProperty(ProductPriceResource::class, 'isGloballySearchable');

    expect($isGloballySearchable->getValue())->toBeFalse()
        ->and(ProductPriceResource::getGloballySearchableAttributes())->toBeEmpty()
        ->and(ProductPriceResource::hasPage('view'))->toBeFalse()
        ->and(ProductPriceResource::hasPage('edit'))->toBeFalse();
});

dataset('searchable resources', [
    'console plans'                => [App\Filament\Console\Resources\Plans\PlanResource::class, false],
    'console properties'           => [App\Filament\Console\Resources\Properties\PropertyResource::class, false],
    'console resellers'            => [App\Filament\Console\Resources\Resellers\ResellerResource::class, false],
    'reseller properties'          => [App\Filament\Reseller\Resources\Properties\PropertyResource::class, true],
    'activity logs'                => [Misaf\VendraActivityLog\Filament\Clusters\Resources\ActivityLogResource::class, false],
    'affiliate commissions'        => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliateCommissions\AffiliateCommissionResource::class, false],
    'affiliate payouts'            => [Misaf\VendraAffiliate\Filament\Clusters\Resources\AffiliatePayouts\AffiliatePayoutResource::class, false],
    'affiliates'                   => [Misaf\VendraAffiliate\Filament\Clusters\Resources\Affiliates\AffiliateResource::class, false],
    'attributes'                   => [Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource::class, false],
    'authentication logs'          => [Misaf\VendraAuthifyLog\Filament\Clusters\Resources\AuthifyLogResource::class, false],
    'blog post categories'         => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPostCategories\BlogPostCategoryResource::class, false],
    'blog posts'                   => [Misaf\VendraBlog\Filament\Clusters\Resources\BlogPosts\BlogPostResource::class, false],
    'carts'                        => [Misaf\VendraCart\Filament\Clusters\Resources\Carts\CartResource::class, false],
    'currencies'                   => [Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\CurrencyResource::class, false],
    'custom page categories'       => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPageCategories\CustomPageCategoryResource::class, false],
    'custom pages'                 => [Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource::class, false],
    'faq categories'               => [Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\FaqCategoryResource::class, false],
    'faqs'                         => [Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource::class, false],
    'language lines'               => [Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\LanguageLineResource::class, false],
    'languages'                    => [Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\LanguageResource::class, false],
    'multimedia'                   => [Misaf\VendraMultimedia\Filament\Clusters\Resources\MultimediaResource::class, false],
    'newsletter subscribers'       => [Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\NewsletterSubscriberResource::class, false],
    'newsletters'                  => [Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\NewsletterResource::class, false],
    'permissions'                  => [Misaf\VendraPermission\Filament\Clusters\Resources\Permissions\PermissionResource::class, false],
    'roles'                        => [Misaf\VendraPermission\Filament\Clusters\Resources\Roles\RoleResource::class, false],
    'product categories'           => [Misaf\VendraProduct\Filament\Clusters\Resources\ProductCategories\ProductCategoryResource::class, false],
    'products'                     => [Misaf\VendraProduct\Filament\Clusters\Resources\Products\ProductResource::class, false],
    'tags'                         => [Misaf\VendraTagger\Filament\Clusters\Resources\Taggers\TaggerResource::class, false],
    'transaction gateways'         => [Misaf\VendraTransaction\Filament\Clusters\Resources\TransactionGateways\TransactionGatewayResource::class, false],
    'transactions'                 => [Misaf\VendraTransaction\Filament\Clusters\Resources\Transactions\TransactionResource::class, false],
    'wallets'                      => [Misaf\VendraTransaction\Filament\Clusters\Resources\Wallets\WalletResource::class, false],
    'user profiles'                => [Misaf\VendraUserProfile\Filament\Clusters\Resources\UserProfileResource::class, false],
    'users'                        => [Misaf\VendraUser\Filament\Clusters\Resources\Users\UserResource::class, false],
]);
