<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Concerns;

use Illuminate\Support\Facades\Config;

trait ResolvesConfiguredPanels
{
    /**
     * Whether the module's Filament plugin should register on the given panel.
     */
    protected function shouldRegisterOnPanel(string $panelId, string $configKey): bool
    {
        return in_array($panelId, $this->configuredPanelIds($configKey), true);
    }

    /**
     * Panel IDs the module registers its plugin on, read from the module's
     * `<configKey>.panels` key (string or list of strings), falling back to
     * the legacy `<configKey>.panel` key and finally to the admin panel.
     *
     * @return array<int, string>
     */
    protected function configuredPanelIds(string $configKey): array
    {
        foreach (["{$configKey}.panels", "{$configKey}.panel"] as $key) {
            $panels = Config::get($key);

            if (is_string($panels)) {
                return [$panels];
            }

            if (is_array($panels)) {
                return array_values(array_filter($panels, is_string(...)));
            }
        }

        return ['admin'];
    }
}
