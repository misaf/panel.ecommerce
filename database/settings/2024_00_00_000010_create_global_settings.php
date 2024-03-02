<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->inGroup('global', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('site_title', 'Hassle-Free Website Creation');
            $blueprint->add('site_description');
            $blueprint->add('site_tags');
            $blueprint->add('site_status', 1);
        });
    }
};
