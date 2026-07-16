<?php

declare(strict_types=1);

use Misaf\VendraLanguage\Support\TranslationCatalog;

test('registered translation namespaces are available as sorted select options', function (): void {
    $options = app(TranslationCatalog::class)->namespaceOptions();
    $sortedNamespaces = array_keys($options);
    sort($sortedNamespaces);

    expect($options)
        ->toHaveKey('vendra-language', 'vendra-language')
        ->and(array_keys($options))->each->toStartWith('vendra-')
        ->and(array_keys($options))->toBe($sortedNamespaces);
});

test('translation files and their keys are available as dependent select options', function (): void {
    $catalog = app(TranslationCatalog::class);

    expect($catalog->groupOptions('vendra-language'))
        ->toHaveKeys(['attributes', 'navigation'])
        ->and($catalog->keyOptions('vendra-language', 'navigation'))
        ->toHaveKeys(['language', 'language_line', 'language_management', 'languages'])
        ->and($catalog->keyOptions('filament-panels', 'resources'))
        ->toBe([])
        ->and($catalog->keyOptions('vendra-language', '../composer'))
        ->toBe([]);
});
