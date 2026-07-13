<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class AttributePlugin implements Plugin
{
    public const string ID = 'vendra-attribute';

    protected string|Closure|null $navigationGroup = null;

    public static function make(): static
    {
        /** @var static $plugin */
        $plugin = app(self::class);

        return $plugin;
    }

    public function getId(): string
    {
        return self::ID;
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
            $configuredGroup = config('vendra-attribute.navigation_group');
            $group = is_string($configuredGroup) ? $configuredGroup : null;
        }

        if ($group instanceof Closure) {
            $group = $group();
        }

        return is_string($group) && '' !== $group
            ? trans_choice($group, 1)
            : trans_choice('vendra-attribute::navigation.content_management', 1);
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(
            in: __DIR__ . '/Filament/Clusters',
            for: 'Misaf\\VendraAttribute\\Filament\\Clusters',
        );
    }

    public function boot(Panel $panel): void {}
}
