<?php

declare(strict_types=1);

namespace Misaf\VendraTagger;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Config;

final class TaggerPlugin implements Plugin
{
    public const string ID = 'vendra-tagger';

    protected string|Closure|null $navigationGroup = null;

    public function getId(): string
    {
        return self::ID;
    }

    public static function make(): static
    {
        /** @var static $plugin */
        $plugin = app(self::class);

        return $plugin;
    }

    public function navigationGroup(string|Closure|null $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): string
    {
        $group = $this->navigationGroup;

        if (null === $group) {
            $configuredGroup = Config::get('vendra-tagger.navigation_group');
            $group = is_string($configuredGroup) ? $configuredGroup : null;
        }

        if ($group instanceof Closure) {
            $group = $group();
        }

        return is_string($group) && '' !== $group
            ? trans_choice($group, 1)
            : __('vendra-support::navigation.groups.Content');
    }

    public function register(Panel $panel): void
    {
        $panel->discoverResources(
            in: __DIR__ . '/Filament/Clusters/Resources',
            for: 'Misaf\\VendraTagger\\Filament\\Clusters\\Resources',
        );
    }

    public function boot(Panel $panel): void {}
}
