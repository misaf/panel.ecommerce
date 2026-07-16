<?php

declare(strict_types=1);

it('keeps verification translations complete and sorted', function (): void {
    expect(__DIR__ . '/../../resources/lang')
        ->toHaveAtLeastTwoLocales('vendra-verification')
        ->toHaveTranslationsInSync('vendra-verification')
        ->toHaveSortedTranslationKeys('vendra-verification');
});
