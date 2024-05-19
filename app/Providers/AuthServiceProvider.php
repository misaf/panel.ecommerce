<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Blog\BlogPost::class                         => \App\Policies\Blog\BlogPostPolicy::class,
        \App\Models\Blog\BlogPostCategory::class                 => \App\Policies\Blog\BlogPostCategoryPolicy::class,
        \App\Models\Currency\Currency::class                     => \App\Policies\Currency\CurrencyPolicy::class,
        \App\Models\Currency\CurrencyCategory::class             => \App\Policies\Currency\CurrencyCategoryPolicy::class,
        \App\Models\Faq\Faq::class                               => \App\Policies\Faq\FaqPolicy::class,
        \App\Models\Faq\FaqCategory::class                       => \App\Policies\Faq\FaqCategoryPolicy::class,
        \App\Models\Geographical\GeographicalCity::class         => \App\Policies\Geographical\GeographicalCityPolicy::class,
        \App\Models\Geographical\GeographicalCountry::class      => \App\Policies\Geographical\GeographicalCountryPolicy::class,
        \App\Models\Geographical\GeographicalNeighborhood::class => \App\Policies\Geographical\GeographicalNeighborhoodPolicy::class,
        \App\Models\Geographical\GeographicalState::class        => \App\Policies\Geographical\GeographicalStatePolicy::class,
        \App\Models\Geographical\GeographicalZone::class         => \App\Policies\Geographical\GeographicalZonePolicy::class,
        \App\Models\Language\Language::class                     => \App\Policies\Language\LanguagePolicy::class,
        \App\Models\Language\LanguageLine::class                 => \App\Policies\Language\LanguageLinePolicy::class,
        \App\Models\Permission\Permission::class                 => \App\Policies\Permission\PermissionPolicy::class,
        \App\Models\Permission\Role::class                       => \App\Policies\Permission\RolePolicy::class,
        \App\Models\Product\Product::class                       => \App\Policies\Product\ProductPolicy::class,
        \App\Models\Product\ProductCategory::class               => \App\Policies\Product\ProductCategoryPolicy::class,
        \App\Models\Product\ProductPrice::class                  => \App\Policies\Product\ProductPricePolicy::class,
        User::class                                              => \App\Policies\User\UserPolicy::class,
        User\UserProfile::class                                  => \App\Policies\User\UserProfilePolicy::class,
        User\UserProfileBalance::class                           => \App\Policies\User\UserProfileBalancePolicy::class,
        User\UserProfileDocument::class                          => \App\Policies\User\UserProfileDocumentPolicy::class,
        User\UserProfilePhone::class                             => \App\Policies\User\UserProfilePhonePolicy::class,
        \App\Models\Order\Order::class                           => \App\Policies\Order\OrderPolicy::class,
        \App\Models\Order\OrderProduct::class                    => \App\Policies\Order\OrderProductPolicy::class,
        \App\Models\Transaction\Transaction::class               => \App\Policies\Transaction\TransactionPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
