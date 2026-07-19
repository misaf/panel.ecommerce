<?php

declare(strict_types=1);

use Filament\Tables\Columns\IconColumn;

dataset('boolean status tables', [
    'attribute'            => 'packages/vendra-attribute/src/Filament/Clusters/Resources/Attributes/Tables/AttributeTable.php',
    'blog post category'   => 'packages/vendra-blog/src/Filament/Clusters/Resources/BlogPostCategories/Tables/BlogPostCategoryTable.php',
    'blog post'            => 'packages/vendra-blog/src/Filament/Clusters/Resources/BlogPosts/Tables/BlogPostTable.php',
    'currency'             => 'packages/vendra-currency/src/Filament/Clusters/Resources/Currencies/Tables/CurrencyTable.php',
    'currency category'    => 'packages/vendra-currency/src/Filament/Clusters/Resources/CurrencyCategories/Tables/CurrencyCategoryTable.php',
    'custom page category' => 'packages/vendra-custom-page/src/Filament/Clusters/Resources/CustomPageCategories/Tables/CustomPageCategoryTable.php',
    'custom page'          => 'packages/vendra-custom-page/src/Filament/Clusters/Resources/CustomPages/Tables/CustomPageTable.php',
    'FAQ category'         => 'packages/vendra-faq/src/Filament/Clusters/Resources/FaqCategories/Tables/FaqCategoryTable.php',
    'FAQ'                  => 'packages/vendra-faq/src/Filament/Clusters/Resources/Faqs/Tables/FaqTable.php',
    'language'             => 'packages/vendra-language/src/Filament/Clusters/Resources/Languages/Tables/LanguageTable.php',
    'product category'     => 'packages/vendra-product/src/Filament/Clusters/Resources/ProductCategories/Tables/ProductCategoryTable.php',
    'transaction gateway'  => 'packages/vendra-transaction/src/Filament/Clusters/Resources/TransactionGateways/Tables/TransactionGatewayTable.php',
    'user profile'         => 'packages/vendra-user-profile/src/Filament/Clusters/Resources/Tables/UserProfileTable.php',
]);

arch('filament tables use interactive toggles instead of icon columns')
    ->expect([
        'App\\Filament',
        'Misaf',
    ])
    ->not->toUse(IconColumn::class);

it('configures boolean status columns like the blog post table', function (string $relativePath): void {
    $contents = file_get_contents(base_path($relativePath));

    expect($contents)->toBeString();

    $matched = preg_match(
        "/ToggleColumn::make\\('status'\\)(?<chain>.*?)(?=\\n\\s*[A-Z][A-Za-z]+Column::make\\(|\\n\\s*];)/s",
        $contents,
        $matches,
    );

    expect($matched)->toBe(1)
        ->and($matches['chain'])
        ->toContain('->label(')
        ->toContain('->onIcon(Heroicon::Bolt)');
})->with('boolean status tables');

it('does not expose a status toggle for authify logs without a status field', function (): void {
    $contents = file_get_contents(base_path(
        'packages/vendra-authify-log/src/Filament/Clusters/Resources/Tables/AuthifyLogTable.php',
    ));

    expect($contents)
        ->toBeString()
        ->not->toContain("::make('status')");
});
