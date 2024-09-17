<?php

declare(strict_types=1);

namespace App\Multitenancy\Tasks;

use Illuminate\Support\Facades\URL;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

final class SwitchAppTask implements SwitchTenantTask
{
    private ?array $original;

    public function __construct(?array $original = null)
    {
        $this->original = $original ?? config('app');
    }

    public function forgetCurrent(): void
    {
        $this->restoreAppConfig($this->original);
    }

    public function makeCurrent(object $tenant): void
    {
        $this->applyTenantConfig($tenant);
    }

    private function restoreAppConfig(array $config): void
    {
        $this->setLocale($config['locale']);
        $this->setName($config['name']);
        $this->setTimezone($config['timezone']);
        $this->setAppUrl($config['url']);
    }

    private function applyTenantConfig(object $tenant): void
    {
        $this->setLocale('fa');
        $this->setName($tenant->name);
        $this->setTimezone('Asia/Tehran');
        $this->setAppUrl(request()->getHost());
    }

    private function setAppUrl(string $url): void
    {
        $originalUrl = parse_url($this->original['url']);

        config([
            'app.url' => "{$originalUrl['scheme']}://{$url}",
        ]);

        URL::forceRootUrl(config('app.url'));
    }

    private function setLocale(string $locale): void
    {
        config(['app.locale' => $locale]);
    }

    private function setName(string $name): void
    {
        config(['app.name' => $name]);
    }

    private function setTimezone(string $timezone): void
    {
        config(['app.timezone' => $timezone]);
    }
}
