<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contract\Language;
use App\Models\Language\Language as LanguageLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(Language::class, LanguageLanguage::class);

        Model::shouldBeStrict( ! $this->app->environment('production'));

        URL::forceScheme('https');

        $this->app['request']->server->set('HTTPS', 'on');

        if ($this->app->environment('production')) {
            Password::defaults(fn() => Password::min(8)->mixedCase());
        }

        // Lang::handleMissingKeysUsing(function (string $key, array $replacements, string $locale) {
        //     info("Missing translation key [{$key}] detected.");

        //     return $key;
        // });

        // DB::listen(fn($query) => Log::info($query->sql, $query->bindings));
    }
}
