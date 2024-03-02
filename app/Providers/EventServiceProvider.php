<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        \App\Models\Blog\BlogPost::class                         => \App\Observers\Blog\BlogPostObserver::class,
        \App\Models\Blog\BlogPostCategory::class                 => \App\Observers\Blog\BlogPostCategoryObserver::class,
        \App\Models\Currency\Currency::class                     => \App\Observers\Currency\CurrencyObserver::class,
        \App\Models\Currency\CurrencyCategory::class             => \App\Observers\Currency\CurrencyCategoryObserver::class,
        \App\Models\Faq\Faq::class                               => \App\Observers\Faq\FaqObserver::class,
        \App\Models\Faq\FaqCategory::class                       => \App\Observers\Faq\FaqCategoryObserver::class,
        \App\Models\Geographical\GeographicalCity::class         => \App\Observers\Geographical\GeographicalCityObserver::class,
        \App\Models\Geographical\GeographicalCountry::class      => \App\Observers\Geographical\GeographicalCountryObserver::class,
        \App\Models\Geographical\GeographicalNeighborhood::class => \App\Observers\Geographical\GeographicalNeighborhoodObserver::class,
        \App\Models\Geographical\GeographicalState::class        => \App\Observers\Geographical\GeographicalStateObserver::class,
        \App\Models\Geographical\GeographicalZone::class         => \App\Observers\Geographical\GeographicalZoneObserver::class,
        \App\Models\Language\Language::class                     => \App\Observers\Language\LanguageObserver::class,
        \App\Models\Language\LanguageLine::class                 => \App\Observers\Language\LanguageLineObserver::class,
        \App\Models\Permission\Permission::class                 => \App\Observers\Permission\PermissionObserver::class,
        \App\Models\Permission\Role::class                       => \App\Observers\Permission\RoleObserver::class,
        \App\Models\Product\Product::class                       => \App\Observers\Product\ProductObserver::class,
        \App\Models\Product\ProductCategory::class               => \App\Observers\Product\ProductCategoryObserver::class,
        \App\Models\Product\ProductPrice::class                  => \App\Observers\Product\ProductPriceObserver::class,
        \App\Models\User::class                                  => \App\Observers\User\UserObserver::class,
        \App\Models\User\UserProfile::class                      => \App\Observers\User\UserProfileObserver::class,
        \App\Models\User\UserProfileBalance::class               => \App\Observers\User\UserProfileBalanceObserver::class,
        \App\Models\User\UserProfileDocument::class              => \App\Observers\User\UserProfileDocumentObserver::class,
        \App\Models\User\UserProfilePhone::class                 => \App\Observers\User\UserProfilePhoneObserver::class,
        \App\Models\Transaction\Transaction::class               => \App\Observers\Transaction\TransactionObserver::class,
    ];

    public function boot(): void {}

    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
