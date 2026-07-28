<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravel\Boost\Install\GuidelineComposer;

/**
 * @param  list<string>  $contractPhrases
 */
it('keeps package guidelines and skills aligned with documented source contracts', function (
    string $package,
    array $contractPhrases,
): void {
    $packagePath = base_path("packages/{$package}");
    $guideline = File::get($packagePath . '/resources/boost/guidelines/core.blade.php');
    $skillFiles = File::allFiles($packagePath . '/resources/boost/skills');

    expect($skillFiles)->toHaveCount(1);

    $skill = File::get($skillFiles[0]->getPathname());

    foreach ($contractPhrases as $contractPhrase) {
        expect($guideline)->toContain($contractPhrase)
            ->and($skill)->toContain($contractPhrase);
    }
})->with([
    'attribute API resources' => [
        'vendra-attribute-api',
        ['AttributeResource', 'AttributeApiResolver', 'selectedAttributeValues'],
    ],
    'cart navigation' => [
        'vendra-cart',
        ['SalesCluster'],
    ],
    'subscription backlog observability' => [
        'vendra-subscription',
        ['ReportSubscriptionPaymentBacklogCommand'],
    ],
    'request and job context' => [
        'vendra-support',
        ['RequestJobContext'],
    ],
    'tenant accessibility and domains' => [
        'vendra-tenant',
        ['Tenant::accessible()', 'ReplaceTenantDomainAction', '/caddy/domain-check'],
    ],
    'testing helpers' => [
        'vendra-testing',
        [
            'toSortByEverySortableColumn()',
            'makeCurrentTestTenantWithFeatures()',
            'setUpFilamentSuperAdminTestContext()',
        ],
    ],
    'optional currency integration' => [
        'vendra-transaction',
        ['CurrencyIntegration'],
    ],
]);

it('only configures Vendra package skills that have a canonical definition', function (): void {
    $canonicalSkills = collect(File::glob(base_path('packages/*/resources/boost/skills/*/SKILL.md')))
        ->map(fn(string $path): string => basename(dirname($path)))
        ->sort()
        ->values()
        ->all();

    $boostConfig = json_decode(File::get(base_path('boost.json')), true, flags: JSON_THROW_ON_ERROR);
    $configuredSkills = collect($boostConfig['skills'])
        ->filter(fn(string $skill): bool => Str::startsWith($skill, 'vendra-'))
        ->sort()
        ->values()
        ->all();

    // boost.json curates which package skills are generated; the set may be a
    // subset, but every configured skill must resolve to a real SKILL.md so a
    // renamed or misspelled reference is still caught.
    expect($canonicalSkills)->toContain(...$configuredSkills);
})->skip(
    fn(): bool => ! File::exists(base_path('boost.json')),
    'boost.json is a local-only Laravel Boost artifact and is not present in CI.',
);

it('keeps the transaction package free of stale direct currency guidance', function (): void {
    $packagePath = base_path('packages/vendra-transaction/resources/boost');
    $guideline = File::get($packagePath . '/guidelines/core.blade.php');
    $skill = File::get($packagePath . '/skills/vendra-transaction-development/SKILL.md');

    foreach ([$guideline, $skill] as $instruction) {
        expect($instruction)
            ->not->toContain('depends on `misaf/vendra-currency`')
            ->not->toContain('Currency coupling goes through `misaf/vendra-currency`');
    }
});

it('composes critical project testing guidance for generated agent instructions', function (): void {
    $guidelines = resolve(GuidelineComposer::class)->compose();

    expect($guidelines)
        ->toContain('Run the smallest relevant test scope first')
        ->toContain('php artisan test --parallel')
        ->toContain('Keep intentionally non-parallel coverage, profiling, mutation testing, and benchmarking commands unchanged');
});
