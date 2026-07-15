<?php

declare(strict_types=1);

use Illuminate\Console\Scheduling\Schedule;
use Misaf\VendraNewsletter\Filament\Clusters\NewslettersCluster;
use Misaf\VendraNewsletter\NewsletterPlugin;
use Misaf\VendraNewsletter\Providers\NewsletterServiceProvider;

it('defaults the newsletter module to the admin panel', function (): void {
    expect(config('vendra-newsletter.panels'))->toBe(['admin']);
});

it('delegates tenant awareness to the tenant resolver instead of module config', function (): void {
    expect(config('vendra-newsletter.tenant_aware'))->toBeNull();
});

it('resolves configured panel ids from an array, string, or legacy panel key', function (): void {
    $provider = new NewsletterServiceProvider(app());
    $method = new ReflectionMethod($provider, 'configuredPanelIds');

    config(['vendra-newsletter.panels' => ['admin', 'vendor']]);

    expect($method->invoke($provider, 'vendra-newsletter'))->toBe(['admin', 'vendor']);

    config(['vendra-newsletter.panels' => 'admin']);

    expect($method->invoke($provider, 'vendra-newsletter'))->toBe(['admin']);

    config([
        'vendra-newsletter.panels' => null,
        'vendra-newsletter.panel'  => 'legacy',
    ]);

    expect($method->invoke($provider, 'vendra-newsletter'))->toBe(['legacy']);
});

it('resolves the module-owned navigation group by default', function (): void {
    expect(NewsletterPlugin::make()->getNavigationGroup())
        ->toBe(__('vendra-support::navigation.groups.Marketing'));
});

it('uses the package label for the cluster breadcrumb', function (): void {
    expect(NewslettersCluster::getClusterBreadcrumb())
        ->toBe(__('vendra-newsletter::navigation.newsletter'));
});

it('lets the navigation group be overridden with a plugin option', function (): void {
    expect(NewsletterPlugin::make()->navigationGroup('Marketing')->getNavigationGroup())
        ->toBe('Marketing')
        ->and(NewsletterPlugin::make()->navigationGroup(fn(): string => 'Grouped')->getNavigationGroup())
        ->toBe('Grouped');
});

it('falls back to the configured navigation group when no plugin option is set', function (): void {
    config(['vendra-newsletter.navigation_group' => 'vendra-newsletter::navigation.newsletter']);

    expect(NewsletterPlugin::make()->getNavigationGroup())
        ->toBe(__('vendra-newsletter::navigation.newsletter'));
});

it('exposes the sending queue configuration through strict accessors', function (): void {
    expect(config('vendra-newsletter.batch_chunk_size'))->toBeInt()
        ->and(config('vendra-newsletter.queue.tries'))->toBeInt()
        ->and(config('vendra-newsletter.queue.timeout'))->toBe(30)
        ->and(config('vendra-newsletter.queue.email_timeout'))->toBe(30);
});

it('exposes a configurable, toggleable schedule', function (): void {
    expect(config('vendra-newsletter.schedule.enabled'))->toBeBool()
        ->and(config('vendra-newsletter.schedule.cron'))->toBeString();
});

it('registers the scheduled send command from the package', function (): void {
    $registered = collect(app(Schedule::class)->events())
        ->contains(fn($event): bool => str_contains((string) $event->command, 'vendra-newsletter:send-scheduled'));

    expect($registered)->toBeTrue();
});
