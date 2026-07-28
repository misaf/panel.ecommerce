<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
        ['AttributeSchema', 'AttributeApiResolver', 'selectedAttributeValues'],
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

it('selects every canonical Vendra package skill for generation', function (): void {
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

    expect($configuredSkills)->toBe($canonicalSkills);
});

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
